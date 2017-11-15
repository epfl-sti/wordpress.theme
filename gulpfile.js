// Defining base paths
var basePaths = {
    js: './js/',
    node: './node_modules/',
    dev: './src/',
    css: './css/'
};


// browser-sync watched files
// automatically reloads the page when files changed
var browserSyncWatchFiles = [
    './css/*.min.css',
    './js/*.min.js',
    './**/*.php'
];


// browser-sync options
// see: https://www.browsersync.io/docs/options/
var browserSyncOptions = {
    proxy: "localhost/wordpress/",
    notify: false
};


// Defining requirements
var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass');
var watch = require('gulp-watch');
var cssnano = require('gulp-cssnano');
var rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var merge2 = require('merge2');
var imagemin = require('gulp-imagemin');
var ignore = require('gulp-ignore');
var rimraf = require('gulp-rimraf');
var clone = require('gulp-clone');
var merge = require('gulp-merge');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();
var del = require('del');
var cleanCSS = require('gulp-clean-css');
var gulpSequence = require('gulp-sequence');
var modernizr = require('gulp-modernizr');


// Run:
// gulp sass + cssnano + rename
// Prepare the min.css for production (with 2 pipes to be sure that "theme.css" == "theme.min.css")
gulp.task('scss-for-prod', function() {
    var source =  gulp.src('./sass/*.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sass());

    var pipe1 = source.pipe(clone())
        .pipe(sourcemaps.write(undefined, { sourceRoot: null }))
        .pipe(gulp.dest('./css'))
        .pipe(rename('custom-editor-style.css'))


    var pipe2 = source.pipe(clone())
        .pipe(minify-css())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./css'));

    return merge(pipe1, pipe2);
});


// Run:
// gulp sourcemaps + sass + reload(browserSync)
// Prepare the child-theme.css for the development environment
gulp.task('scss-for-dev', function() {
    gulp.src('./sass/*.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sass())
        .pipe(sourcemaps.write(undefined, { sourceRoot: null }))
        .pipe(gulp.dest('./css'))
});

gulp.task('watch-scss', ['browser-sync'], function () {
    gulp.watch('./sass/**/*.scss', ['scss-for-dev']);
});


// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task('sass', function () {
    var stream = gulp.src('./sass/*.scss')
        .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
        .pipe(sass())
        .pipe(gulp.dest('./css'))
        .pipe(rename('custom-editor-style.css'))
    return stream;
});


// Run:
// gulp watch
// Starts watcher. Watcher runs gulp sass task on changes
gulp.task('watch', function () {
    gulp.watch('./sass/**/*.scss', ['styles']);
    gulp.watch([basePaths.dev + 'js/**/*.js','js/**/*.js','!js/theme.js','!js/theme.min.js'], ['scripts']);

    //Inside the watch task.
    gulp.watch('./img/**', ['imagemin'])
});

// Run:
// gulp imagemin
// Running image optimizing task
gulp.task('imagemin', function(){
    gulp.src('img/src/**')
    .pipe(imagemin())
    .pipe(gulp.dest('img'))
});


// Run:
// gulp cssnano
// Minifies CSS files
gulp.task('cssnano', function(){
  return gulp.src('./css/theme.css')
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano({discardComments: {removeAll: true}}))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./css/'))
});

gulp.task('minify-css', function() {
  return gulp.src('./css/theme.css')
  .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(cleanCSS({compatibility: '*'}))
    .pipe(plumber({
            errorHandler: function (err) {
                console.log(err);
                this.emit('end');
            }
        }))
    .pipe(rename({suffix: '.min'}))
     .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./css/'));
});

gulp.task('cleancss', function() {
  return gulp.src('./css/*.min.css', { read: false }) // much faster
    .pipe(ignore('theme.css'))
    .pipe(rimraf());
});

gulp.task('styles', ['copy-assets'], function(callback){ gulpSequence('sass', 'minify-css')(callback) });
 

// Run:
// gulp browser-sync
// Starts browser-sync task for starting the server.
gulp.task('browser-sync', function() {
    browserSync.init(browserSyncWatchFiles, browserSyncOptions);
});


// Run:
// gulp watch-bs
// Starts watcher with browser-sync. Browser-sync reloads page automatically on your browser
gulp.task('watch-bs', ['browser-sync', 'watch', 'scripts'], function () { });


// Run: 
// gulp scripts. 
// Uglifies and concat all JS files into one
gulp.task('scripts', ['copy-assets'], function() {
    var scripts = [
        basePaths.node + 'bootstrap/dist/js/bootstrap.js',
        basePaths.dev  + 'js/megamenu.js',
        basePaths.dev  + 'js/carousel.js'
    ];
  return merge2(
      gulp.src(scripts)
          .pipe(concat('theme.min.js'))
          .pipe(uglify())
          .pipe(gulp.dest('./js/')),

      gulp.src(scripts)
          .pipe(concat('theme.js'))
          .pipe(gulp.dest('./js/')));
});

// Deleting any built or copied files
gulp.task('clean-source', function () {
  return del(['css/normalize.css', 'js/core.*', 'js/jquery.*',
              'js/modernizr.*', 'js/theme.js', 'js/theme.min.js',
             'css/theme.*']);
});

// Run:
// gulp copy-assets.
// Copy all needed dependency assets files from node_modules assets to themes /js, /scss and /fonts folder. Run this task after npm install

////////////////// Copy pre-built assets /////////////////////////
// Copy to serving directories all assets that are invoked by either
// header.php, footer.php or inc/enqueue.php
gulp.task('copy-assets', function() {
    return merge2(
        gulp.src(basePaths.node + 'font-awesome/fonts/**/*.{ttf,woff,woff2,eof,svg}')
            .pipe(gulp.dest('./fonts')),

        gulp.src(basePaths.node + 'jquery/dist/*.js')
            .pipe(gulp.dest(basePaths.js)),

        gulp.src(basePaths.node + 'jquery-touchswipe/jquery.touchSwipe*.js')
            .pipe(gulp.dest(basePaths.js)),

        gulp.src(basePaths.node + 'normalize.css/*.css')
            .pipe(gulp.dest(basePaths.css)),

        gulp.src(basePaths.node + 'popper.js/dist/umd/popper.min.js')
            .pipe(gulp.dest(basePaths.js)),
        gulp.src(basePaths.node + 'popper.js/dist/umd/popper.js')
            .pipe(gulp.dest(basePaths.js))
    );
});


// Run
// gulp dist
// Copies the files to the /dist folder for distribution as simple theme
gulp.task('dist', ['clean-dist'], function() {
    gulp.src(['**/*','!bower_components','!bower_components/**','!node_modules','!node_modules/**','!src','!src/**','!dist','!dist/**','!dist-product','!dist-product/**','!sass','!sass/**','!readme.txt','!readme.md','!package.json','!gulpfile.js','!CHANGELOG.md','!.travis.yml','!jshintignore', '!codesniffer.ruleset.xml', '*'])
    .pipe(gulp.dest('dist/'))
});

// Deleting any file inside the /src folder
gulp.task('clean-dist', function () {
  return del(['dist/**/*',]);
});

// Run
// gulp dist-product
// Copies the files to the /dist-prod folder for distribution as theme with all assets
gulp.task('dist-product', ['clean-dist-product'], function() {
    gulp.src(['**/*','!bower_components','!bower_components/**','!node_modules','!node_modules/**','!dist','!dist/**','!dist-product','!dist-product/**', '*'])
    .pipe(gulp.dest('dist-product/'))
});

// Deleting any file inside the /src folder
gulp.task('clean-dist-product', function () {
  return del(['dist-product/**/*',]);
});

gulp.task('modernizr', ['copy-assets', 'scripts'], function() {
  gulp.src(['./js/*.js', '!js/modernizr*'])
    .pipe(modernizr())
    .pipe(gulp.dest("js/"))
});

// The default rule: build everything once, then stop
gulp.task('default', ['copy-assets', 'scripts', 'styles', 'modernizr'])
