var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');

// theme directory
var themeDir = 'ngstacks';

// .scss input
var cssSrc  = './src/scss/**/*.scss';
// .css output
var cssDest = './src/css';

// Options
var sassOptions = {
  errLogToConsole: true,
  includePaths: [cssSrc],
  outputStyle: 'nested'
};

// Default options for autoprefixer are:
// Browsers with over 1% market share,
// Last 2 versions of all browsers,
// Firefox ESR,
// Opera 12.1
//
// Example options:
// var autoprefixerOptions = {
//   browsers: ['last 2 versions', '> 5%', 'Firefox ESR']
// };

gulp.task('sass', function () {
  return gulp
    .src(cssSrc)
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(sourcemaps.write('./'))
    // .pipe(autoprefixer()) // use default options (see above)
    .pipe(gulp.dest(cssDest));
});

gulp.task('watch', function() {
  return gulp
    // Watch the input folder for change,
    // and run `sass` task when something happens
    .watch('./src/scss/**', gulp.series(['sass']))
    // When there is a change,
    // log a message in the console
    .on('change', function(event) {
      console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    });
});

gulp.task('default', gulp.series(['sass', 'watch']));

// The array passed as second argument of the task(..) function is a list of dependency tasks. Basically, it tells Gulp to run those tasks before running the one specified as a third argument (if any).
