require('es6-promise').polyfill();

var gulp = require('gulp');
var pkg = require('./package.json');
var del = require('del');
var sass = require('gulp-sass');
var postcss = require('gulp-postcss');
var cssnext = require('postcss-cssnext');
var cssnano = require('cssnano');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var typescript  = require('typescript');
var ts = require('gulp-typescript');
var uglify = require('gulp-uglify');
var plumber = require('gulp-plumber');
var header = require('gulp-header');
var project = ts.createProject('tsconfig.json', {
    typescript: typescript,
    // out: 'script.js'
});

var paths = {
    'ts': './src/AppBundle/Resources/public/ts/',
    'js': './src/AppBundle/Resources/public/js/',
    'scss': './src/AppBundle/Resources/public/scss/',
    'css': './src/AppBundle/Resources/public/css/',
    'font': './src/AppBundle/Resources/public/fonts/'
};

gulp.task('clean:css', function(cb) {
    return del([paths.font + '*', paths.css + '*'], { dot: true }, cb);
});

gulp.task('clean:js', function(cb) {
    return del([paths.js + '*'], { dot: true }, cb);
});

// scssコンパイル
gulp.task('scss', ['clean:css'], function() {
    var processors = [
        cssnext(),
        cssnano()
    ];

    gulp.src('./node_modules/font-awesome/fonts/*.*')
        .pipe(gulp.dest(paths.font));

    return gulp.src([paths.scss + '**/*.scss', '!' + paths.scss + '**/_*.scss'])
               .pipe(plumber())
               .pipe(sass())
               .pipe(autoprefixer())
               .pipe(postcss(processors))  // 'es6-promise'を読み込む or Nodejs0.12
               .pipe(header('/* copyright <%= pkg.name %> */', {pkg: pkg}))
               .pipe(sourcemaps.write())
               .pipe(gulp.dest(paths.css));
});

// TypeScriptコンパイル
gulp.task('ts', ['clean:js'], function() {
    return gulp.src([paths.ts + '**/*.{ts,tsx}', '!' + paths.ts + '**/_*.{ts,tsx}'])
               .pipe(plumber())
               .pipe(ts(project))
               .pipe(uglify())
               .pipe(header('/* copyright <%= pkg.name %> */', {pkg: pkg}))
               .pipe(gulp.dest(paths.js));
});

gulp.task('watch', function() {
    gulp.watch(paths.scss + '**/*.scss', ['scss']);
    gulp.watch(paths.ts + '**/*.{ts,tsx}', ['ts']);
});

gulp.task('default', ['scss', 'ts', 'watch']);
