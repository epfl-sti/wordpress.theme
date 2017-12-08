// Defining requirements
const gulp = require('gulp');
const util = require('util');
const watch = require('gulp-watch');
const browserSync = require('browser-sync').create();
const ignore = require('gulp-ignore');
const lazypipe = require('lazypipe');
const plumber = require('gulp-plumber');
const concat = require('gulp-concat');
const clone = require('gulp-clone');
const through2 = require("through2");
const sass = require('gulp-sass');
const imagemin = require('gulp-imagemin');
const sourcemaps = require('gulp-sourcemaps');
const cleanCSS = require('gulp-clean-css');
const modernizr = require('gulp-modernizr');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const del = require('del');
const argv = require("yargs").argv;

// browser-sync options
// see: https://www.browsersync.io/docs/options/
var browserSyncOptions = {
    proxy: (argv.url || "https://localhost/sti/"),
    notify: false
};

// Run any of:
// gulp default
// gulp all
// gulp
// The default rule: build everything once, then stop
gulp.task('default', ['copy-assets', 'imagemin', 'scripts', 'sass'])
gulp.task('all', ['default'])

// Run:
// gulp watch
// Starts watcher and run appropriate tasks on changes
gulp.task('watch', ['default'], function () {
    gulp.watch('./sass/**/*', ['sass']);
    gulp.watch(['js/**/*.js'], ['scripts']);
    gulp.watch('./img/src/**', ['imagemin'])
});

// Run:
// gulp browser-sync
// Like "gulp watch", but run a browser and keep reloading it everytime
// the build products change
gulp.task('browser-sync', ['watch'], function() {
    browserSync.init(
        ['assets/**/*', '**/*.php'],
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
// Concat all JS files (incl. compiled Vue files) into assets/theme{,.min}.js
gulp.task('scripts', function() {
    return gulp.src([
        'node_modules/popper.js/dist/umd/popper.js',  // Bootstrap dependency, must come before it
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'js/**/*.js'
    ])
        .pipe(bundleJS('theme.js'))
        .pipe(assetsDest())  // Save un-minified, then continue
        .pipe(uglifyJS({suffix: '.min'}))
        .pipe(assetsDest());
});

// Run:
// gulp copy-assets
// Copy all needed dependency assets files from node_modules to assets/
gulp.task('copy-assets', function() {
    var npm_goodies = [
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
