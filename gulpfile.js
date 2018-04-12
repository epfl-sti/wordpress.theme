/**
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

// The following modules are part of node.js core:
const fs = require('fs');
const path = require('path');
const https = require('https');

// The following are provided by an NPM package (and therefore should
// each be in the "devDependencies" or "dependencies" section of
// package.json):
const gulp = require('gulp');
const watch = require('gulp-watch');
const browserSync = require('browser-sync').create();
const ignore = require('gulp-ignore');
const lazypipe = require('lazypipe');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const clone = require('gulp-clone');
const through2 = require('through2');
const merge2 = require('merge2');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const cssnext = require('postcss-cssnext');
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
const axios = require('axios');
const pify = require('pify');
const argv = require('yargs').argv;

// Permit site-level customization
if (fs.existsSync("gulpfile.js.local")) {
    require("./gulpfile.js.local")(argv);
}

const wordpressURL = argv.url || "https://localhost/";

/**
 * Browser-sync reloads pages for us during development
 *
 * @see https://www.browsersync.io/docs/options/
 */
function browserSyncOptions() {
    var browserSyncOptions = {
        proxy: wordpressURL,
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
     * then need to enroll into your OS' trusted certificate store. */
    const keypair = { cert: "devsupport/browser-sync.crt",
                      key:  "devsupport/browser-sync.key" };

    if (fs.existsSync(keypair.key) && fs.existsSync(keypair.cert)) {
        browserSyncOptions.https = keypair;
    }
    return browserSyncOptions;
}

/**
 * SASS gives us compile-time @include's in CSS, templating (with
 * variables) and more. Bootstrap itself is written in SASS so that we
 * can easily tweak things such as link colors etc.
 */
function sassOptions() {
    return {
        importer: function(url, prev, done) {
            // This turns @import ~foo/bar into
            // @import [....]/node_modules/foo/bar:
            return tildeImporter(url, __dirname, done)
        }
    }
}

/**
 * Babel digests modern JS into something even IE8 can grok
 */
function babelOptions() {
    return {
        "presets": [
            ["env", {
                "targets": {
                    /* IE 7 is a non-goal (not supported by Vue) */
                    "browsers": ["> 5%", "ie >= 8"]
                }
            }]
        ],
        "plugins": ["transform-vue-jsx"]
    }
}

/**
 * CSSNext turns :fullscreen into :-moz-full-screen etc., and more
 *
 * @see https://cssnext.io/
 */
function postcssOptions() {
    return [cssnext()]
}


// Run any of:
// gulp default
// gulp all
// gulp
// The default rule: build everything once, then stop
gulp.task('default', ['copy-assets', 'imagemin',
                      'scripts', 'admin-scripts', 'sass', 'clear-caches'])
gulp.task('all', ['default'])

// Run:
// gulp watch
// Starts watcher and run appropriate tasks on changes
gulp.task('watch', ['default'], function () {
    gulp.watch(['./sass/theme.scss', './sass/*/**/*', ], ['sass']);
    gulp.watch(['js/**/*.js'], ['scripts']);
    gulp.watch(['newsletter-theme/**/*.js', 'newsletter-theme/**/*.vue',
                'newsletter-theme/**/*.scss',
                'wp-admin/**/*.js',],
               ['admin-scripts']);
    gulp.watch('./img/src/**', ['imagemin'])
    gulp.watch(['inc/maxmegamenu.php'], ['clear-caches']);
});

// Run:
// gulp browser-sync
// Like "gulp watch", and also start a browser and reload it as required
gulp.task('browser-sync', ['watch'], function() {
    browserSync.watch(
        [
            // Source code
            '**/*.php',
            'css/**/*',
            'newsletter-theme/*.css',

            // Assets built by Gulp
            'assets/**/*',

            // Assets built from PHP
            '../../uploads/*megamenu/style*.css',

            // Save ourselves the initialization costs and also the
            // spurious reloads, as chokidar (the library behind
            // browserSync) is both very defensive and buggy as far as
            // symlinks are concerned:
            '!node_modules'
        ],
        {ignoreInitial: true},
        browserSync.reload);

    // There are more events that cause a browser reload; look for
    // `.pipe(browserSync.stream())` elsewhere in this file.
    browserSync.init(browserSyncOptions());
});

// Run:
// gulp sass
// Compiles the theme's SCSS files to CSS
gulp.task('sass', function () {
    return gulp.src('./sass/theme.scss')
        .pipe(processSASS())
        .pipe(assetsDest())
        .pipe(browserSync.stream());
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
        'node_modules/cookieconsent/src/cookieconsent.js', // https://github.com/insites/cookieconsent
        'js/**/*.js'
    ])
        .pipe(bundleJS('theme.js'))
        .pipe(assetsDest())  // Save un-minified, then continue
        .pipe(uglifyJS())
        .pipe(assetsDest())
        .pipe(browserSync.stream());
});

// Run:
// gulp admin-scripts
// Browserify all Vue and JS files files for the admin pages into
// assets/admin-theme{,.min}.js and assets/admin-theme{,.min}.css
gulp.task('admin-scripts', function() {
    const vue_entrypoints = gulp.src([
        // Browserify will chase dependencies by itself
        // (on-filesystem, bypassing the Gulp pipeline)
        './newsletter-theme/newsletter-composer.js'
    ]);

    const sass_sources = gulp.src([
        './newsletter-theme/composer.scss'
    ]);

    const vue_pipeline = vue_entrypoints
        .pipe(bro({  // Bro is a modern wrapper for browserify
            debug: true,  // Produce a sourcemap
            cacheFile: "assets/browserify-cache.json",
            transform: [
                /* Turn Vue components into pure JS */
                ['vueify', {
                  /* Use advanced JS in Vue */
                  babel: babelOptions(),
                  /* You can say <style lang="scss"></style> in Vue: */
                  sass: sassOptions(),
                  postcss: postcssOptions()
                }],
                /* One more bout of Babel for "straight" (non-Vue) JS files: */
                babelify.configure(babelOptions()),
                /* You can use assert in the test suite, and the browser won't see it. */
                'unassertify'
            ]
        }))
        .pipe(assetsDest())  // Save non-minified, then continue
        /* Source maps cause the Chrome debugger to reveal all source
         * files in their pristine splendor, *provided* it doesn't
         * silently refuse to do so for reasons such as a dodgy SSL
         * cert (see the comments near const keypair, above)
         */
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(uglifyJS())
        .pipe(assetsDest());

    /* This is for stand-alone SASS files, not the snippets in .vue files */
    const css_pipeline = sass_sources
        .pipe(rename("newsletter-composer.scss"))
        .pipe(processSASS())
        .pipe(assetsDest());

    return merge2(vue_pipeline, css_pipeline)
        .pipe(browserSync.stream());
});

// Run:
// gulp copy-assets
// Copy all needed dependency assets files from node_modules to assets/
// Note that these are front-end theme dependencies only;
// admin-scripts handles its dependencies separately
gulp.task('copy-assets', function() {
    var npm_goodies = [
        'core-js/client/core.min.js*',
        'jquery/dist/*.js',
        'jquery-touchswipe/jquery.touchSwipe*.js',
        'normalize.css/*.css',
        'ionicons/dist/css/ionicons.min.css*'
    ];
    return gulp.src(npm_goodies.map((path) => './node_modules/' + path))
        .pipe(assetsDest())
        .pipe(browserSync.stream());
});

gulp.task('clear-caches', function() {
    return reloadCaches();
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
 * @return A DuplexStream that uglifies individual JS files found in it,
 *         and drops the other files (does not forward them).
 *
 * The output is both developer- and production-friendly. "debugger"
 * statements are kept, so that one may do development out of the
 * uglified files - Thereby minimizing the likelihood of problems that
 * only exist in production code. All files are renamed from *.js to
 * *.min.js after uglifying, and (if initialized by caller beforehand)
 * sourcemap files are output alongside as *.min.js.map
 */
function uglifyJS() {
    return lazypipe()
        .pipe(() => ignore.include(new RegExp('js$')))
        .pipe(() => uglify( {compress: {drop_debugger: false}} ))
        .pipe(() => rename({suffix: '.min'}))
        .pipe(() => sourcemaps.write('.'))
        ();
}

/**
 * @return A DuplexStream that turns every .scss file inside it
 *         into two CSS files (plain and minified) and their corresponding
 *         sourcemaps.
 */
function processSASS() {
    return lazypipe()
        .pipe(() => sourcemaps.init())
        .pipe(() => sass(sassOptions()))
        .pipe(() => postcss(postcssOptions()))
        .pipe(() => assetsDest())  // Save un-minified, then continue
        .pipe(() => cleanCSS())
        .pipe(() => rename({suffix: '.min'}))
        .pipe(() => sourcemaps.write('.'))
        ()
        /* We don't want an error in the SASS code to stop "gulp watch": */
        .on("error", function (err) {
            console.log(err);
            this.emit('end');
        });
}

/**
 * Ask devsupport/reload-caches.php to do its thing.
 */
function reloadCaches() {
    var url = wordpressURL;
    if (! url.endsWith('/')) {
        url += "/";
    }
    url += "wp-content/themes/" + path.basename(__dirname) +
        "/devsupport/reload-caches.php";

    const shibbolethFilename = __dirname + "/../../uploads/theme-epfl-sti-reload-caches.OK";
    return pify(fs.writeFile)(shibbolethFilename, "")
        .then(function() {
            console.log("Hitting " + url + " ...");
            const ax = axios.create({
                httpsAgent: new https.Agent({
                    rejectUnauthorized: false
                })
            });
            return ax.get(url);
        })
        .then(function (response) {
            console.log("... OK");
//            browserSync.reload()
        })
        .catch(function (error) {
            console.log("... ERROR", error);
        });
}

