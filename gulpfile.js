/*
 * gulpfile.js is like the Makefile for client-side Web assets:
 * JavaScript code obviously, but also CSS, minified images.
 *
 * Using gulpfile.js requires npm, as in "npm install" or "npm start"
 * (sans double-quotes). Once you have done either of these once, you can
 * also say "./node_modules/.bin/gulp sass" (sans double-quotes) or replace
 * "sass" with the name of any of the gulp.task's found below.
 * Some of the tasks take command-line arguments, which you pass as either
 *
 *   ./node_modules/.bin/gulp watch --url=https://localhost:444/sti/
 *
 * or through npm, protected with -- like this:
 *
 *   npm start -- --url=https://localhost:444/sti/
 */
const fs = require('fs');  // Part of node.js core
const gulp = require('gulp');
const util = require('util');
const watch = require('gulp-watch');
const browserSync = require('browser-sync').create();
const ignore = require('gulp-ignore');
const lazypipe = require('lazypipe');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const clone = require('gulp-clone');
const through2 = require('through2');
const sass = require('gulp-sass');
var cssnext = require('postcss-cssnext');
const tildeImporter = require('node-sass-tilde-importer');
const imagemin = require('gulp-imagemin');
const sourcemaps = require('gulp-sourcemaps');
const bro = require('gulp-bro');
const babelify = require('babelify');
const vueify = require('vueify');
const cleanCSS = require('gulp-clean-css');
const modernizr = require('gulp-modernizr');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const del = require('del');
const argv = require('yargs').argv;

// browser-sync options
// see: https://www.browsersync.io/docs/options/
var browserSyncOptions = {
    proxy: (argv.url || "https://localhost/"),
    notify: false,
};

if (argv.browser) {
    browserSyncOptions.browser = argv.browser;
}

/* If Chrome doesn't trust the certificate presented by browserSync,
 * *even if you have already clicked your way through the security
 * exception*, it won't reliably load source maps (see details at
 * https://github.com/BrowserSync/browser-sync/issues/639#issuecomment-351125049)
 * There is a kit to make your own self-signed certificate under
 * devsupport/ (see instructions in devsupport/openssl.cnf), which you
 * then need to enroll into your OS' trusted certificate store.
 */
(function() {
  const keypair = { cert: "devsupport/browser-sync.crt",
                    key:  "devsupport/browser-sync.key" };

  if (fs.existsSync(keypair.key) && fs.existsSync(keypair.cert)) {
    browserSyncOptions.https = keypair;
  }
}());

// Run any of:
// gulp default
// gulp all
// gulp
// The default rule: build everything once, then stop
gulp.task('default', ['copy-assets', 'imagemin',
                      'scripts', 'admin-scripts', 'sass'])
gulp.task('all', ['default'])

// Run:
// gulp watch
// Starts watcher and run appropriate tasks on changes
gulp.task('watch', ['default'], function () {
    gulp.watch('./sass/**/*', ['sass']);
    gulp.watch(['js/**/*.js'], ['scripts']);
    gulp.watch(['newsletter-theme/**/*.js', 'newsletter-theme/**/*.vue'],
               ['admin-scripts']);
    gulp.watch('./img/src/**', ['imagemin'])
});

// Run:
// gulp browser-sync
// Like "gulp watch", but run a browser and keep reloading it everytime
// the build products change
gulp.task('browser-sync', ['watch'], function() {
    browserSync.init(
        ['assets/**/*', '**/*.php', 'newsletter-theme/*.css'],
        browserSyncOptions);
});

// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task('sass', function () {
    return gulp.src('./sass/*.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(assetsDest())  // Save un-minified, then continue
        .pipe(cleanCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(sourcemaps.write('.'))
        .pipe(assetsDest());
});

// Run:
// gulp imagemin
// Running image optimizing task
gulp.task('imagemin', function(){
    return gulp.src('img/src/**')
        .pipe(imagemin())
        .pipe(assetsDest())
});

// Run:
// gulp scripts
// Concat all JS files into assets/theme{,.min}.js
gulp.task('scripts', function() {
    return gulp.src([
        'node_modules/popper.js/dist/umd/popper.js',  // Bootstrap dependency, must come before it
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/anchor-js/anchor.min.js', // https://www.bryanbraun.com/anchorjs/
        'js/**/*.js'
    ])
        .pipe(bundleJS('theme.js'))
        .pipe(assetsDest())  // Save un-minified, then continue
        .pipe(uglifyJS({suffix: '.min'}))
        .pipe(assetsDest());
});

// Run:
// gulp admin-scripts
// Render all Vue and JS files (and even CSS) files for the admin pages into
// assets/admin-theme{,.min}.js
gulp.task('admin-scripts', function() {
    /* Babel digests modern JS into something even IE8 can grok */
    const babelOptions = () => ({
      "presets": [
        ["env", {
          "targets": {
            /* IE 7 is a non-goal (not supported by Vue) */
            "browsers": ["> 5%", "ie >= 8"]
          }
        }]
      ]
    });
    return gulp.src([
        // Source just the entry point; Browserify will chase dependencies
        // by itself
        './newsletter-theme/composer.js'
    ])
        .pipe(bro({
            debug: true,  // Produce a sourcemap
            transform: [
                /* Turn Vue components into pure JS (even the CSS snippets) */
                ['vueify', {
                  /* This options object uses the same keys as vue.config.js */
                  babel: babelOptions(),
                  /* You can say <style lang="scss"></style> in Vue. Also,
                   * correct CSS happens to also be correct SASS, so you can
                   * also @import a CSS straight out of an NPM package
                   * (https://github.com/vuejs-templates/webpack/issues/604)
                   */
                  sass: {
                    importer: function(url, prev, done) {
                      // This turns @import ~foo/bar into
                      // @import [....]/node_modules/foo/bar:
                      return tildeImporter(url, __dirname, done)
                    }
                  },
                  // Turn :fullscreen into :-moz-full-screen etc., and more
                  // See https://cssnext.io/
                  postcss: [cssnext()]
                }],
                /* One more bout of Babel for "straight" (non-Vue) JS files: */
                babelify.configure(babelOptions()),
                /* You can use assert in the test suite, and the browser won't see it. */
                'unassertify'
            ]
        }))
        .pipe(rename("newsletter-composer.js"))
        .pipe(assetsDest())  // Save non-minified, then continue
        /* Source maps cause the Chrome debugger to reveal all source
         * files in their pristine splendor, *provided* it doesn't
         * silently refuse to do so for reasons such as a dodgy SSL
         * cert (see the comments near const keypair, above)
         */
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(uglifyJS({suffix: '.min'}))
        .pipe(assetsDest());
});

// Run:
// gulp copy-assets
// Copy all needed dependency assets files from node_modules to assets/
// Note that these are front-end theme dependencies only;
// admin-scripts handles its dependencies separately
gulp.task('copy-assets', function() {
    var npm_goodies = [
        'core-js/client/core.min.js*',
        'font-awesome/fonts/**/*.{ttf,woff,woff2,eof,svg}',
        'jquery/dist/*.js',
        'jquery-touchswipe/jquery.touchSwipe*.js',
        'normalize.css/*.css',
        'ionicons/dist/css/ionicons.min.css*'
    ];
    return gulp.src(npm_goodies.map((path) => './node_modules/' + path))
            .pipe(assetsDest());
});

// Run:
// gulp dist
// Copies the files to the /dist folder for distribution as simple theme
gulp.task('dist', ['clean-dist'], function() {
    gulp.src(['**/*',,'!node_modules','!node_modules/**','!src','!src/**','!dist','!dist/**','!dist-prod','!dist-prod/**','!sass','!sass/**','!readme.txt','!readme.md','!package.json','!gulpfile.js','!CHANGELOG.md','!.travis.yml','!jshintignore', '!codesniffer.ruleset.xml', '*'])
    .pipe(gulp.dest('dist/'))
});

// Run:
// gulp dist-prod
// Copies the files to the /dist-prod folder for distribution as theme with all assets
gulp.task('dist-prod', ['clean-dist-prod'], function() {
    gulp.src(['**/*','!node_modules','!node_modules/**','!dist','!dist/**','!dist-prod','!dist-prod/**', '*'])
    .pipe(gulp.dest('dist-prod/'))
});

// Run:
// gulp clean
// Deletes any built or copied files
gulp.task('clean', function () {
  return del(['assets/**/*']);
});

// Run:
// gulp clean-dist
// Deletes the "dist" subdirectory
gulp.task('clean-dist', function () {
  return del(['dist/**/*',]);
});

// Run:
// gulp clean-dist-prod
// Deletes the "dist-prod" subdirectory
gulp.task('clean-dist-prod', function () {
  return del(['dist-prod/**/*',]);
});

// Support functions
function assetsDest() { return  gulp.dest('assets/') }

function pipeLog(formatter) {
    if (formatter === undefined) formatter = '%s';
    return through2.obj(function(file, enc, cb) {
        console.log(util.format(formatter, file.path));
        cb(null, file);
    })
}

/**
 * Bundle all files piped to it into a single file called
 * destination_filename, together with a custom trimmed-down version
 * of Modernizr.
 *
 * Pipe the return of this function to a pipeline that produces JS files.
 */
function bundleJS(destination_filename) {
    const cloneSink = clone.sink();
    return lazypipe()
        .pipe(() => cloneSink)   // Stash away all the files, see https://www.npmjs.com/package/gulp-clone
        .pipe(modernizr)
        .pipe(cloneSink.tap)  // Add back all the files
        .pipe(sourcemaps.init)
        .pipe(() => concat(destination_filename))
        .pipe(() => sourcemaps.write('.'))
        ();
}

/**
 * @returns A DuplexStream that uglifies JS files found in it, and drops
 *          the other files (does not forward them).
 *
 * "debugger" statements are kept, so that one may do development out
 * of the uglified files - Thereby minimizing the likelihood of problems
 * that only exist in production code.
 */
function uglifyJS(rename_opts) {
    const cloneSink = clone.sink();
    return lazypipe()
        .pipe(() => ignore.include(new RegExp('js$')))
        .pipe(() => uglify( {compress: {drop_debugger: false}} ))
        .pipe(() => rename(rename_opts))
        .pipe(() => sourcemaps.write('.'))
        ();
}
