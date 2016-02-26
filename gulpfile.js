// Include gulp
var gulp     = require( 'gulp' ),
    wpPot    = require( 'gulp-wp-pot' ),
    sort     = require( 'gulp-sort' ),
    sass     = require( 'gulp-sass' ),
    rename   = require( 'gulp-rename' ),
    uglify   = require( 'gulp-uglify' ),
    imagemin = require( 'gulp-imagemin' ),
    cssnano  = require( 'gulp-cssnano' ),
    newer    = require( 'gulp-newer' ),
 //   notify   = require( 'gulp-notify' ),
    replace  = require( 'gulp-replace' ),
    sequence = require( 'run-sequence' ),
    uglify   = require( 'gulp-uglify' ),
    jshint   = require( 'gulp-jshint' ),
    phpcbf   = require( 'gulp-phpcbf' ),
    phpcs    = require( 'gulp-phpcs' ),
    gutil    = require( 'gutil' ),
    del      = require( 'del' ),
    bower    = require( 'gulp-bower' );

// Configs
var config = {
  fontsDest: './boldgrid-theme-framework/assets/fonts',
  src: './src',
  dist: './boldgrid-theme-framework',
  bower: './bower_components' ,
  jsDest: './boldgrid-theme-framework/assets/js',
  scss_dest: '../boldgrid-theme-framework/inc/assets/scss',
  scss_src: './inc/assets/scss',
  css_dest: '../boldgrid-theme-framework/inc/assets/css',
  css_src: './inc/assets/css',
  img_dest: '../boldgrid-theme-framework/inc/assets/img',
  img_src: './inc/assets/img/**/*',
  layouts_src: './layouts',
  layouts_dest: '../boldgrid-theme-framework/layouts',
  scss_minify: 'compressed', // or uncompressed for dev
};

// Create a bower task to retrieve bower_components on build
gulp.task( 'bower', function(  ) { 
  return bower(  )
     .pipe( gulp.dest( config.bower ) ) 
} );

// Clean distribution on build.
gulp.task( 'clean', function(  ) {
  return del( [config.dist] );
} );

// Javascript Dependencies
gulp.task( 'jsDeps', function(  ) {
  // jQuery Stellar - Check
  gulp.src( config.bower + '/jquery.stellar/jquery.stellar*.js' )
    .pipe( gulp.dest( config.jsDest + '/jquery-stellar' ) );
  // Bootstrap
  gulp.src( config.bower + '/bootstrap-sass/assets/javascripts/bootstrap.*' )
    .pipe( gulp.dest( config.jsDest + '/bootstrap' ) );
  // sass.js - Check
  gulp.src( config.bower + '/sass.js/dist/**/*' )
    .pipe( gulp.dest( config.jsDest + '/sass-js' ) );
  // Wowjs - Check
  gulp.src( config.bower + '/wow/dist/**/*' )
    .pipe( gulp.dest( config.jsDest + '/wow' ) );
  // Color-js
  gulp.src( config.bower + '/color-js/color.js' )
    .pipe( gulp.dest( config.jsDest + '/color-js' ) );
  gulp.src( config.bower + '/color-js/color.js' )
    .pipe( uglify(  ) )
    .pipe( rename({ suffix: '.min' }) )
    .pipe( gulp.dest( config.jsDest + '/color-js' ) );
} );

// Font Dependencies
gulp.task( 'fontDeps', function(  ) {
  // Font Awesome
  gulp.src( config.bower + '/font-awesome/fonts/**/*.{ttf,woff,woff2,eot,otf,svg}' )
    .pipe( gulp.dest( config.fontsDest ) )
   // .pipe( notify( { message: 'Font Dependencies Loaded', onLast: true } ) );
} );

// PHP Dependencies
gulp.task( 'phpDeps', function(  ) {
  // Leafo SCSSPHP Compiler
  gulp.src([
    '!' + config.bower + '/scssphp/tests',
    '!' + config.bower + '/scssphp/tests/**',
    config.bower + '/scssphp/**/*.php'
  ])
    .pipe( gulp.dest( config.dist + '/includes/scssphp' ) );
  // Kirki Customizer Controls
  gulp.src([
    '!' + config.bower + '/kirki/assets',
    '!' + config.bower + '/kirki/assets/**',
    '!' + config.bower + '/kirki/tests',
    '!' + config.bower + '/kirki/tests/**',
    config.bower + '/kirki/**/*.{php,pot}',
   ])
    .pipe( replace( 'kirki-logo.svg', 'boldgrid-logo.svg' ) )
    .pipe( replace( '/** Button styles **/', '/** Button styles **' ) )
    .pipe( gulp.dest( config.dist + '/includes/kirki' ) );
  gulp.src( config.bower + '/kirki/assets/**/*.{json,png,css,map,scss,js}' )
    .pipe( gulp.dest( config.dist + '/includes/kirki/assets' ) );
  gulp.src( config.src + '/assets/img/boldgrid-logo.svg' )
    .pipe( gulp.dest( config.dist + '/includes/kirki/assets/images' ) );
  // Black Studio TinyMCE Widget
  gulp.src( config.src + '/includes/black-studio-tinymce-widget/**/*' )
    .pipe( gulp.dest( config.dist + '/includes/black-studio-tinymce-widget' ) );
} );

// Copy Framework Files
gulp.task( 'frameworkFiles', function(  ) {
  gulp.src([
    '!' + config.src + '/includes/black-studio-tinymce-widget',
    '!' + config.src + '/includes/black-studio-tinymce-widget/**',
    config.src + '/**/*.{php,txt,json,css,scss}',
   ])
    .pipe( gulp.dest( config.dist ) );
} );

// Framework Images.  Pipe through newer images only!
gulp.task( 'images', function(  ) {
  return  gulp.src( [ config.src + '/assets/img/**/*.{png,jpg,gif}'] )
    .pipe( newer( config.dist + '/assets/img' ) )
    .pipe( imagemin( {
      optimizationLevel: 7,
      progressive: true,
      interlaced: true
    } ) )
    .pipe( gulp.dest( config.dist + '/assets/img' ) )
   // .pipe( notify( { message: 'Image minification complete', onLast: true } ) );
} );

// Setup Translate
gulp.task( 'translate', function (  ) {
  return gulp.src( config.src + '/**/*.php' )
    .pipe( sort() )
    .pipe( wpPot({
      domain: 'bgtfw',
      destFile: 'boldgrid-theme-framework.pot',
      package: 'boldgrid_theme_framework',
      bugReport: 'https://boldgrid.com',
      team: 'The BoldGrid Team <support@boldgrid.com>'
    }) )
    .pipe( gulp.dest( config.dist + '/languages' ) )
    //.pipe( notify( { message: 'Theme Translation complete', onLast: true } ) );
} );

// JSHint
gulp.task( 'jsHint', function(  ) {
  return gulp.src( [config.src + '/assets/js/**/*.js'] )
    .pipe( jshint() )
    .pipe( jshint.reporter( 'jshint-stylish' ) )
    .pipe( jshint.reporter( 'fail' ) );
});

// Minify & Copy JS
gulp.task( 'frameworkJs', function(  ) {
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe( uglify() )
    .pipe( rename({
      suffix: '.min'
    }) )
    .pipe( gulp.dest( config.dist + '/assets/js' ) );
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe( gulp.dest( config.dist + '/assets/js' ) );
});

// Copy SCSS & CSS deps.
gulp.task( 'scssDeps', function(  ) {
  // Bootstrap
  gulp.src( config.bower + '/bootstrap-sass/assets/stylesheets/**/*' )
    .pipe( gulp.dest( config.dist + '/assets/scss/bootstrap' ) );
  // Font-Awesome
  gulp.src( config.bower + '/font-awesome/scss/**/*.scss' )
    .pipe( replace( '../fonts', '../../fonts' ) )
    .pipe( gulp.dest( config.dist + '/assets/scss/font-awesome' ) );
  // Underscores
  gulp.src( './inc/assets/scss/underscores/**/*.scss' )
    .pipe( gulp.dest( config.scss_dest + '/underscores' ) );
  // Animate.css
  gulp.src( config.bower + '/animate.css/animate.*' )
    .pipe( gulp.dest( config.dist + '/assets/css/animate-css' ) );
} );

// Compile SCSS
gulp.task( 'scssCompile', function(  ) {
  gulp.src( [
    '!' + config.dist + '/assets/scss/bootstrap.scss',
    '!' + config.dist + '/assets/scss/custom-color/**/*',
    config.dist + '/assets/scss/**/*.scss'] )
    .pipe( sass( {
      includePaths: [
        config.dist + 'assets/scss/',
        config.dist + 'assets/scss/bootstrap',
      ]
    } ) )
    .pipe( sass.sync(  ).on( 'error', sass.logError ) )
    .pipe( gulp.dest( config.dist + '/assets/css' ) )
    .pipe( cssnano({
        discardComments: {removeAll: true}
      }) )
    .pipe( rename({ suffix: '.min' }) )
    .pipe( gulp.dest( config.dist + '/assets/css' ) );
} );
// Bootstrap Compile
gulp.task( 'bootstrapCompile', function(  ) {
  gulp.src( config.dist + '/assets/scss/bootstrap.scss' )
    .pipe( sass(  ) )
    .pipe( sass.sync(  ).on( 'error', sass.logError ) )
    .pipe( gulp.dest( config.dist + '/assets/css/bootstrap' ) )
    .pipe( cssnano({ discardComments: { removeAll: true } }) )
    .pipe( rename( { suffix: '.min' } ) )
    .pipe( gulp.dest( config.dist + '/assets/css/bootstrap' ) )
  //  .pipe( notify( { message: 'SCSS compile complete', onLast: true } ) );
} );

// Watch for changes and recompile scss
gulp.task( 'sass:watch', function(  ) {
  gulp.watch( config.scss + '/**/*.scss', ['scssCompile'] );
} );

// WordPress Standard PHP Beautify
gulp.task( 'phpcbf', function () {
  return gulp.src( 'src/**/*.php' )
  .pipe( phpcbf({
    bin: 'phpcbf',
    standard: 'WordPress',
    warningSeverity: 0
  }))
  .on( 'error', gutil.log )
  .pipe( gulp.dest( 'src' ) );
});

// PHP Code Sniffer
gulp.task('codeSniffer', function () {
    return gulp.src([
      '!' + config.src + '/includes/black-studio-tinymce-widget',
      '!' + config.src + '/includes/black-studio-tinymce-widget/**',
      'src/**/*.php'
      ])
      // Validate files using PHP Code Sniffer
      .pipe( phpcs({
          standard: 'WordPress',
          warningSeverity: 0
      }) )
      // Log all problems that was found
      .pipe( phpcs.reporter( 'log' ) );
});

// Tasks
gulp.task( 'build', function( cb ) {
  sequence(
    'clean',
    'bower',
    ['jsHint', 'frameworkJs'],
    ['images', 'scssDeps', 'jsDeps', 'fontDeps', 'phpDeps', 'frameworkFiles', 'translate' ],
    ['scssCompile', 'bootstrapCompile'],
    cb
  );
});

gulp.task( 'prebuild', ['images', 'scssDeps', 'jsDeps', 'fontDeps', 'phpDeps', 'frameworkFiles', 'translate' ]);
