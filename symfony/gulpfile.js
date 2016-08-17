require('es6-promise').polyfill();

var gulp = require('gulp');
var pkg = require('./package.json');
var sass = require('gulp-sass');
var postcss = require('gulp-postcss');
var cssnext = require('postcss-cssnext');
var autoprefixer = require('gulp-autoprefixer');
var typescript = require('gulp-typescript');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var header = require('gulp-header');

var paths = {
    'ts': './src/AppBundle/Resources/public/ts/',
    'js': './src/AppBundle/Resources/public/js/',
    'scss': './src/AppBundle/Resources/public/scss/',
    'css': './src/AppBundle/Resources/public/css/'
};

gulp.task('scss', function() {
    var processors = [
        cssnext()
    ];

    return gulp.src(paths.scss + '**/*.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(postcss(processors))  // 'es6-promise'を読み込む or Nodejs0.12
        .pipe(header('/* copyright <%= pkg.name %> */', {pkg: pkg}))
        .pipe(gulp.dest(paths.css));
});

gulp.task('ts', function() {
    var options =  {
        out: 'script.js'
    };

    return gulp.src(paths.ts + '**/*.ts')
               .pipe(plumber())
               .pipe(typescript(options))
               .pipe(uglify())
               .pipe(header('/* copyright <%= pkg.name %> */', {pkg: pkg}))
               .pipe(gulp.dest(paths.js));
});

gulp.task('watch', function() {
    gulp.watch(paths.scss + '**/*.scss', ['scss']);
    gulp.watch(paths.ts + '**/*.ts', ['ts']);
});

gulp.task('default', ['scss', 'ts', 'watch']);
