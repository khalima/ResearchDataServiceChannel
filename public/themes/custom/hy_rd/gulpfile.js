/**
 * Get dependencies and config.
 */
var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var cleancss = require('gulp-clean-css');
var globbing = require('node-sass-globbing');
var exec = require('child_process').exec;
var prompt = require('gulp-prompt');
var runSequence = require('run-sequence');
var sass = require('gulp-sass');
var watch = require('gulp-watch');

var config = {
  localSassPath: './sass',
  bowerSassPath: './libraries/styleguide/sass',
  bowerDir: './libraries/styleguide',
  imagesDir: './libraries/styleguide/images',
  localImagesDir: './images',
  styleGuideBranch: 'develop'
};

var sass_config = {
  importer: globbing,
  outputStyle: 'expanded',
  includePaths: [
    'node_modules/normalize.css/',
    'node_modules/breakpoint-sass/stylesheets/',
    'node_modules/singularitygs/stylesheets/'
  ]
};

/**
 * Task for changing Style guide branch.
 * Very handy when developing Style guide.
 *
 * Notice! Bower needs to be installed globally to run this command successfully.
 */
gulp.task('branch', function() {
  gulp.src('./*', {buffer:false})
    .pipe(prompt.prompt({
      type: 'input',
      name: 'branch',
      message: 'Which branch/release of Style guide would you like to use?'
    }, function(res){
      if (res.branch) {
        config.styleGuideBranch = res.branch;
      }
      console.log('Downloading Style guide with ' + config.styleGuideBranch + ' branch...');

      return series([
        'bower uninstall styleguide --force',
        'bower install styleguide=https://github.com/UniversityofHelsinki/Styleguide.git#' + config.styleGuideBranch + ' --force',
        'git checkout -- bower.json'
      ], function(err){
        console.log('Downloaded Style guide with branch ' + config.styleGuideBranch + '. You may want to run \'gulp compile\' to update the changes in css files.');
      });
    })
  );
});


/**
 * Task for getting latest version of develop branch from Style guide repository.
 */
gulp.task('bowerUpdate', function(cb) {
  console.log('Updating Style guide with dev branch...');

  exec('$(npm bin)/bower update', function(error, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);

    if (error !== null) {
      console.error('Error: ' . error);
    }
    cb();
  });
});


/**
 * Task for getting latest version of master branch from Style guide repository.
 */
gulp.task('bowerUpdateProduction', function(cb) {
  console.log('Updating Style guide with master branch...');

  exec('$(npm bin)/bower update --production', function(error, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);

    if (error !== null) {
      console.error('Error: ' . error);
    }
    cb();
  });
});

/**
 * Task for copying images from Style guide.
 */
gulp.task('copyFiles', function(cb) {
  gulp.src(config.imagesDir + '/**/*.{jpg,png,svg,gif,jpeg}')
    .pipe(gulp.dest(config.localImagesDir));
  cb();
});

/**
 * Task for copying fonts from Style guide.
 */
gulp.task('copyFonts', function (cb) {
  gulp.src(config.bowerDir + '/fonts/**/*')
    .pipe(gulp.dest('./fonts'));
  cb();
});

/**
 * Task for compiling SCSS files.
 */
gulp.task('compile', function () {
  gulp.src('sass/**/*.scss')
    .pipe(sass(sass_config)
      .on('error', sass.logError))
    .pipe(autoprefixer({
      browsers: ['last 4 versions']
    }))
    .pipe(cleancss({compatibility: 'ie8', keepSpecialComments: 0}))
    .pipe(gulp.dest('css'));
});

/**
 * Task for running watch for SCSS files.
 */
gulp.task('watch', ['compile'], function () {
  gulp.watch(config.localSassPath + '/**/*.scss', ['compile']);
});

/**
 * Task for updating the Style guide with latest develop branch.
 * Also copies files and fonts from Style guide.
 */
gulp.task('update', function(cb) {
  runSequence('bowerUpdate', ['copyFiles', 'copyFonts'], cb);
});


/**
 * Task for updating the Style guide with latest master branch.
 * Also copies files and fonts from Style guide.
 */
gulp.task('update-prod', function(cb) {
  runSequence('bowerUpdateProduction', ['copyFiles', 'copyFonts'], cb);
});


/**
 * Task for building the styles.
 * Updates Style guide to latest develop, copies files and fonts
 * and compiles SCSS.
 */
gulp.task('build', function(cb) {
  runSequence('update', 'compile', cb);
});


/**
 * Task for building the production styles.
 * Updates Styleguide to latest develop, copies files and fonts
 * and compiles SCSS.
 */
gulp.task('build-prod', function(cb) {
  runSequence('bowerUpdateProduction', ['copyFiles', 'copyFonts'], 'compile', cb);
});


/**
 * Task for default tasks (build).
 */
gulp.task('default', ['build']);



/**
 * Helper function to run execute commands.commands in series.
 * Borrowed from https://github.com/mout/mout/ build script
 * author Miller Medeiros
 * released under MIT License
 * version: 0.1.0 (2013/02/01)
 *
 * @param cmd
 * @param cb
 */
var execute = function(cmd, cb){
  // this would be way easier on a shell/bash script :P
  var child_process = require('child_process');
  var parts = cmd.split(/\s+/g);
  var p = child_process.exec(parts[0], parts.slice(1), {stdio: 'inherit'});
  p.on('exit', function(code){
    var err = null;
    if (code) {
      err = new Error('command "'+ cmd +'" exited with wrong status code "'+ code +'"');
      err.code = code;
      err.cmd = cmd;
    }
    if (cb) cb(err);
  });
};

/**
 * Helper function to run commands in series.
 * Borrowed from https://github.com/mout/mout/ build script
 * author Miller Medeiros
 * released under MIT License
 * version: 0.1.0 (2013/02/01)
 *
 * @param cmds
 * @param cb
 */
var series = function(cmds, cb){
  var execNext = function(){
    execute(cmds.shift(), function(err){
      if (err) {
        cb(err);
      } else {
        if (cmds.length) execNext();
        else cb(null);
      }
    });
  };
  execNext();
};
