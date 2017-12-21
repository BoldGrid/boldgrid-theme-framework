// Include gulp
var gulp = require('gulp'),
  wpPot = require('gulp-wp-pot'),
  sort = require('gulp-sort'),
  sass = require('gulp-sass'),
  rename = require('gulp-rename'),
  uglify = require('gulp-uglify'),
  imagemin = require('gulp-imagemin'),
  cssnano = require('gulp-cssnano'),
  newer = require('gulp-newer'),
  //   notify   = require( 'gulp-notify' ),
  replace = require('gulp-replace'),
  sequence = require('run-sequence'),
  fontImage = require("googlefonts-sprite-generator"),
  jshint = require('gulp-jshint'),
  phpcbf = require('gulp-phpcbf'),
  phpcs = require('gulp-phpcs'),
  gutil = require('gutil'),
  del = require('del'),
  clean = require('gulp-clean'),
  fs = require('fs'),
  argv = require('yargs').argv,
  modernizr = require('gulp-modernizr'),
  jscs = require('gulp-jscs'),
  bower = require('gulp-bower');

// Configs
var config = {
  fontsDest: './boldgrid-theme-framework/assets/fonts',
  src: './src',
  dist: './boldgrid-theme-framework',
  bower: './bower_components',
  jsDest: './boldgrid-theme-framework/assets/js',
  scss_dest: '../boldgrid-theme-framework/inc/assets/scss',
  scss_src: './inc/assets/scss',
  css_dest: '../boldgrid-theme-framework/inc/assets/css',
  css_src: './inc/assets/css',
  fontsSrc: './inc/assets/fonts',
  img_dest: '../boldgrid-theme-framework/inc/assets/img',
  img_src: './inc/assets/img/**/*',
  layouts_src: './layouts',
  layouts_dest: '../boldgrid-theme-framework/layouts',
  scss_minify: 'compressed' // or uncompressed for dev
};

// Create a bower task to retrieve bower_components on build
gulp.task('bower', function () {
  return bower()
    .pipe(gulp.dest(config.bower));
});

// Create CSS file for font-family control based on webfonts.json.
gulp.task('fontFamilyCss', function () {
  // The latest web fonts file can be found at https://www.googleapis.com/webfonts/v1/webfonts?key={key-goes-here}
  var fileContent = fs.readFileSync(config.src + "/assets/json/webfonts.json", "utf8"),
    webFonts = JSON.parse(fileContent),
    outFilename = 'font-family-controls.min.css',
    css = '',
    family,
    position;

  for (var key in webFonts.items) {
    family = webFonts.items[key].family;
    position = -5 + (key * -40);

    css += '.select2-container--default .select2-selection__rendered[title="' + family + '"] {color: transparent; background-image: url(../../img/web-fonts.png); background-repeat: no-repeat; background-position: 8px ' + (position + 8) + 'px;}';
    css += '[id^="select2-"][id$="-' + family + '"] { color: transparent; background-image: url(../../img/web-fonts.png); background-repeat: no-repeat; background-position:8px ' + position + 'px;}';
    css += '[id^="select2-"][id$="-' + family + '"]:hover, [id^="select2-"][id$="-' + family + '"].select2-results__option--highlighted[aria-selected] { color: transparent; }';
  }

  // Write to file.
  fs.writeFileSync(outFilename, css);
  gulp.src(outFilename)
    .pipe(clean(config.dist + '/assets/css/customizer/' + outFilename))
    .pipe(gulp.dest(config.dist + '/assets/css/customizer'));
});

// Google Fonts image generator
// Reguires Phatnom JS - npm install -g phantomjs
// If on windows, install python, visual studio express | c++11 compiler
gulp.task('googlefonts-image', function () {
  var googleApiKey = argv.google_api_key;
  if (!googleApiKey) {
    console.log('gulp googlefonts-image --google_api_key={Key Goes Here}');
    return;
  }

  fontImage.getImage({
    callback: function (base64Data) {
      //console.log( base64Data );
      require('fs').writeFile(config.src + '/assets/img/web-fonts.png', base64Data, 'base64', function (err) {
        console.log(err);
      });
    },
    port: 1224,
    options: {
      lineHeigth: '40px',
      fontSize: '25px',
      width: '400px'
    },
    googleAPIKey: googleApiKey
  });

  return 1;
});

// Clean distribution on build.
gulp.task('clean', function () {
  return del([config.dist]);
});

// Javascript Dependencies
gulp.task('jsDeps', function () {
  // jQuery Stellar - Check
  gulp.src(config.bower + '/jquery.stellar/jquery.stellar*.js')
    .pipe(gulp.dest(config.jsDest + '/jquery-stellar'));
  // Bootstrap
  gulp.src(config.bower + '/bootstrap-sass/assets/javascripts/bootstrap.*')
    .pipe(gulp.dest(config.jsDest + '/bootstrap'));
  gulp.src(config.bower + '/smartmenus/dist/**/jquery.*.js')
    .pipe(gulp.dest(config.jsDest + '/smartmenus'));
  // Jasny Bootstrap
  gulp.src(config.bower + '/jasny-bootstrap/js/offcanvas.js')
    .pipe(gulp.dest(config.jsDest + '/offcanvas'));
  gulp.src(config.bower + '/jasny-bootstrap/js/offcanvas.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.jsDest + '/offcanvas'));
  // Nicescroll.
  gulp.src(config.bower + '/jquery.nicescroll/dist/*.{js,png}')
    .pipe(gulp.dest(config.jsDest + '/niceScroll'));
  // jQuery goup.
  gulp.src([
    config.bower + '/jquery-goup/src/*.js',
    config.bower + '/jquery-goup/*.js'])
    .pipe(gulp.dest(config.jsDest + '/goup'));
  // sass.js - Check
  gulp.src(config.bower + '/sass.js/dist/**/*')
    .pipe(gulp.dest(config.jsDest + '/sass-js'));
  // Wowjs - Check
  gulp.src(config.bower + '/wow/dist/**/*')
    .pipe(gulp.dest(config.jsDest + '/wow'));
  // Color-js
  gulp.src(config.bower + '/color-js/color.js')
    .pipe(gulp.dest(config.jsDest + '/color-js'));
  gulp.src(config.bower + '/color-js/color.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.jsDest + '/color-js'));
});

// Font Dependencies
gulp.task('fontDeps', function () {
  // Font Awesome
  gulp.src(config.bower + '/font-awesome/fonts/**/*.{ttf,woff,woff2,eot,otf,svg}')
    .pipe(gulp.dest(config.fontsDest));
  // .pipe( notify( { message: 'Font Dependencies Loaded', onLast: true } ) );
  // Custom Icons
  gulp.src(config.src + '/assets/fonts/*.{ttf,woff,woff2,eot,otf,svg}')
    .pipe(gulp.dest(config.fontsDest));
});

// PHP Dependencies
gulp.task('phpDeps', function () {
  // Leafo SCSSPHP Compiler
  gulp.src([
    '!' + config.bower + '/scssphp/scss.inc.php',
    '!' + config.bower + '/scssphp/tests',
    '!' + config.bower + '/scssphp/tests/**',
    config.bower + '/scssphp/**/*.php'
  ])
    .pipe(gulp.dest(config.dist + '/includes/scssphp'));
  gulp.src(config.bower + '/scssphp/scss.inc.php')
    .pipe(replace('5.4', '5.3', true))
    .pipe(gulp.dest(config.dist + '/includes/scssphp'));
  // Kirki Customizer Controls.
  gulp.src([
    '!' + config.bower + '/kirki/assets',
    '!' + config.bower + '/kirki/assets/**',
    '!' + config.bower + '/kirki/tests',
    '!' + config.bower + '/kirki/tests/**',
    config.bower + '/kirki/**',
  ])
    .pipe(replace('kirki-logo.svg', 'boldgrid-logo.svg'))
    .pipe(gulp.dest(config.dist + '/includes/kirki'));
  // Get Kirki CSS.
  gulp.src(config.bower + '/kirki/assets/**/*.{css,map}')
    .pipe(replace('Button styles **/', 'Button styles **', true))
    .pipe(gulp.dest(config.dist + '/includes/kirki/assets'));
  // Get Kirki Assets.
  gulp.src(config.bower + '/kirki/assets/**/*.{png,scss,js}')
    .pipe(gulp.dest(config.dist + '/includes/kirki/assets'));
  gulp.src(config.src + "/assets/json/webfonts.json")
    .pipe(gulp.dest(config.dist + '/includes/kirki/assets/json'));
  // Add BoldGrid Logo to Kirki.
  gulp.src(config.src + '/assets/img/boldgrid-logo.svg')
    .pipe(gulp.dest(config.dist + '/includes/kirki/assets/images'));
  // Black Studio TinyMCE Widget.
  gulp.src(config.src + '/includes/black-studio-tinymce-widget/**/*')
    .pipe(gulp.dest(config.dist + '/includes/black-studio-tinymce-widget'));
});

// Copy Framework Files.
gulp.task('frameworkFiles', function () {
  return gulp.src([
    '!' + config.src + '/includes/black-studio-tinymce-widget',
    '!' + config.src + '/includes/black-studio-tinymce-widget/**',
    config.src + '/**/*.{php,txt,json,css,scss,mo,po,pot}',
  ])
    .pipe(gulp.dest(config.dist));
});

//Converto readme.txt to md
gulp.task('readme', function () {
  gulp.src('./README.md')
    .pipe(gulp.dest(config.dist));
});

// Framework Images.  Pipe through newer images only!
gulp.task('images', function () {
  return gulp.src([config.src + '/assets/img/**/*.{png,jpg,gif}'])
    .pipe(newer(config.dist + '/assets/img'))
    //.pipe( changed( config.src + '/assets/img' ) )
    .pipe(imagemin({
      optimizationLevel: 7,
      progressive: true,
      interlaced: true
    }))
    .pipe(gulp.dest(config.dist + '/assets/img'))
  // .pipe( notify( { message: 'Image minification complete', onLast: true } ) );
});

// Setup Translate.
gulp.task('translate', function () {
  return gulp.src(config.src + '/**/*.php')
    .pipe(sort())
    .pipe(wpPot({
      domain: 'bgtfw',
      destFile: 'boldgrid-theme-framework.pot',
      package: 'boldgrid_theme_framework',
      bugReport: 'https://boldgrid.com',
      team: 'The BoldGrid Team <support@boldgrid.com>'
    }))
    .pipe(gulp.dest(config.dist + '/languages'));
  //.pipe( notify( { message: 'Theme Translation complete', onLast: true } ) );
});

// JSHint
gulp.task('jsHint', function () {
  return gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'))
    .pipe(jshint.reporter('fail'));
});

gulp.task('jscs', function () {
  return gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(jscs())
    .pipe(jscs.reporter())
    .pipe(jscs.reporter('fail'));
});

// Minify & Copy JS
gulp.task('frameworkJs', function () {
  // Minified Files.
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(uglify().on('error', gutil.log))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest(config.dist + '/assets/js'));

  // Unminified Files.
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(gulp.dest(config.dist + '/assets/js'));
});

// Modernizr
// Minify & Copy JS
gulp.task('modernizr', function () {
  // Minified Files.
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(modernizr(require('./modernizr-config.json')))
    .pipe(uglify().on('error', gutil.log))
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest(config.dist + '/assets/js'));

  // Unminified Files.
  gulp.src([config.src + '/assets/js/**/*.js'])
    .pipe(modernizr(require('./modernizr-config.json')))
    .pipe(gulp.dest(config.dist + '/assets/js'));
});

// Copy SCSS & CSS deps.
gulp.task('scssDeps', function () {
  // Bootstrap
  gulp.src(config.bower + '/bootstrap-sass/assets/stylesheets/**/*')
    .pipe(replace(/@import "bootstrap\/buttons";/, '//@import "bootstrap/buttons";'))
    .pipe(replace(/@import "bootstrap\/button-groups";/, '//@import "bootstrap/button-groups";'))
    .pipe(gulp.dest(config.dist + '/assets/scss/bootstrap'));
  // Font-Awesome
  gulp.src(config.bower + '/font-awesome/scss/**/*.scss')
    .pipe(replace('../fonts', '../../fonts'))
    .pipe(gulp.dest(config.dist + '/assets/scss/font-awesome'));
  // Custom Icons
  gulp.src(config.scss_src + '/icomoon/style.scss')
    .pipe(gulp.dest(config.dist + '/assets/scss/icomoon'));
  // Animate.css
  gulp.src(config.bower + '/animate.css/animate.*')
    .pipe(gulp.dest(config.dist + '/assets/css/animate-css'));
  // Underscores
  gulp.src(config.bower + '/Buttons/scss/**/*.scss')
    .pipe(replace('$values: #{$values}, #{$i}px #{$i}px #{$kolor};', "$values: unquote(#{$values}+', '+#{$i}+'px '+#{$i}+'px '+#{$kolor});"))
    .pipe(replace("$values: #{$values}, unquote($i * -1 + 'px') #{$i}px #{$kolor};", "$values: unquote(#{$values}+', '+#{$i * -1}+'px '+#{$i}+'px '+#{$kolor});"))
    .pipe(replace("background: linear-gradient(top,", "background: linear-gradient("))
    .pipe(gulp.dest(config.dist + '/assets/scss/buttons'));
  gulp.src(config.bower + '/select2-bootstrap-css/select2-bootstrap*.css')
    .pipe(gulp.dest(config.dist + '/assets/css/select2-bootstrap'));

  gulp.src(config.bower + '/smartmenus/dist/css/sm-core-css.css')
    .pipe(gulp.dest(config.dist + '/assets/css/smartmenus'));
  gulp.src(config.bower + '/smartmenus/dist/addons/**/jquery.*.css')
    .pipe(gulp.dest(config.dist + '/assets/css/smartmenus'));

  // boldgrid-components.
  gulp.src('./node_modules/@boldgrid/components/dist/css/components.*')
    .pipe(gulp.dest(config.dist + '/assets/css'));
});

// Compile SCSS
gulp.task('scssCompile', function () {
  gulp.src([
    '!' + config.dist + '/assets/scss/bootstrap.scss',
    '!' + config.dist + '/assets/scss/custom-color/**/*',
    config.dist + '/assets/scss/**/*.scss'])
    .pipe(sass({
      includePaths: [
        config.dist + 'assets/scss/',
        config.dist + 'assets/scss/bootstrap',
      ]
    }).on('error', sass.logError))
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(gulp.dest(config.dist + '/assets/css'))
    .pipe(cssnano({
      discardComments: { removeAll: true },
      zindex: false
    }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.dist + '/assets/css'));
});
// Bootstrap Compile
gulp.task('bootstrapCompile', function () {
  gulp.src(config.dist + '/assets/scss/bootstrap.scss')
    .pipe(sass())
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(gulp.dest(config.dist + '/assets/css/bootstrap'))
    .pipe(cssnano({ discardComments: { removeAll: true } }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.dist + '/assets/css/bootstrap'))
  //  .pipe( notify( { message: 'SCSS compile complete', onLast: true } ) );
});

// Watch for changes and recompile scss
gulp.task('sass:watch', function () {
  gulp.watch(config.scss + '/**/*.scss', ['scssCompile']);
});

// WordPress Standard PHP Beautify
gulp.task('phpcbf', function () {
  return gulp.src('src/**/*.php')
    .pipe(phpcbf({
      bin: 'phpcbf',
      standard: 'WordPress',
      warningSeverity: 0
    }))
    .on('error', gutil.log)
    .pipe(gulp.dest('src'));
});

// PHP Code Sniffer
gulp.task('codeSniffer', function () {
  return gulp.src([
    '!' + config.src + '/includes/black-studio-tinymce-widget',
    '!' + config.src + '/includes/black-studio-tinymce-widget/**',
    'src/**/*.php'
  ])
    // Validate files using PHP Code Sniffer
    .pipe(phpcs({
      standard: 'WordPress',
      warningSeverity: 0
    }))
    // Log all problems that was found
    .pipe(phpcs.reporter('log'));
});

// Tasks
gulp.task('build', function (cb) {
  sequence(
    'clean',
    'bower',
    'readme',
    ['jsHint', 'jscs', 'frameworkJs'],
    ['scssDeps', 'jsDeps', 'modernizr', 'fontDeps', 'phpDeps', 'frameworkFiles', 'translate'],
    'images',
    ['scssCompile', 'bootstrapCompile'],
    'fontFamilyCss',
    cb
  );
});

// Tasks
gulp.task('qbuild', function (cb) {
  sequence(
    'readme',
    ['jsHint', 'jscs', 'frameworkJs'],
    ['scssDeps', 'jsDeps', 'modernizr', 'fontDeps', 'phpDeps', 'frameworkFiles', 'translate'],
    ['scssCompile', 'bootstrapCompile'],
    'fontFamilyCss',
    cb
  );
});

gulp.task('css-js', function (cb) {
  sequence('frameworkFiles', 'frameworkJs', 'scssCompile', cb);
});

gulp.task('prebuild', ['images', 'scssDeps', 'jsDeps', 'fontDeps', 'phpDeps', 'frameworkFiles', 'translate']);

gulp.task('watch', function () {
  gulp.watch(config.src + '/**/*', ['css-js']);
});
