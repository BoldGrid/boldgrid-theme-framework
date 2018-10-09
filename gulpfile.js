// Include gulp
var gulp = require('gulp'),
  wpPot = require('gulp-wp-pot'),
  sort = require('gulp-sort'),
  sass = require('gulp-sass'),
  rename = require('gulp-rename'),
  uglify = require('gulp-uglify-es').default,
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
  shell = require('gulp-shell'),
  del = require('del'),
  clean = require('gulp-clean'),
  fs = require('fs'),
  argv = require('yargs').argv,
  modernizr = require('gulp-modernizr'),
  jscs = require('gulp-jscs'),
  postcss = require('gulp-postcss'),
  inject = require('gulp-inject-string');

// Configs
var config = {
  fontsDest: './boldgrid-theme-framework/assets/fonts',
  src: './src',
  dist: './boldgrid-theme-framework',
  node_modules: './node_modules',
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

// Create CSS file for font-family control based on webfonts.json.
//
// To avoid misalignment, we need to work off the same list as our googlefonts-image gulp task.
// Before running, make sure assets/json/webfonts.json is up to date with
// https://www.googleapis.com/webfonts/v1/webfonts?key={key-goes-here}
gulp.task('fontFamilyCss', function () {
  var fileContent = fs.readFileSync(config.src + "/assets/json/webfonts.json", "utf8"),
    webFonts = JSON.parse(fileContent),
    outFilename = 'font-family-controls.min.css',
    css = '',
    family,
    position;

  for (var key in webFonts.items) {
    family = webFonts.items[key].family;
    position = -5 + (key * -37);

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
//
// Reguires Phatnom JS - npm install -g phantomjs
// If on windows, install python, visual studio express | c++11 compiler
//
// Troubleshooting:
// # Within node_modules/googlefonts-sprint-generator/app.js use dnodeOpts: {weak: false}, as shown
//   here: https://pastebin.com/uZGZP5Ms
// # Preview the image used within sprites by visiting the following page:
//   node_modules/googlefonts-sprint-generator/generators/generator_phantom.html
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
      // If above 37px, not all fonts will be rendered within web-fonts.png.
      lineHeigth: '37px',
      fontSize: '25px',
      width: '500px'
    },
    googleAPIKey: googleApiKey
  });

  return 1;
});

// Create dist folder.
gulp.task('dist', function () {
  return gulp.src('*.*', {read: false})
    .pipe(gulp.dest('./boldgrid-theme-framework'))
});
// Clean distribution on build.
gulp.task('clean', function () {
  return del([config.dist]);
});

// Javascript Dependencies
gulp.task('jsDeps', function () {
  // jQuery Stellar - Check
  gulp.src(config.node_modules + '/jquery.stellar/jquery.stellar*.js')
    .pipe(gulp.dest(config.jsDest + '/jquery-stellar'));
  gulp.src(config.node_modules + '/jquery.stellar/jquery.stellar*.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.jsDest + '/jquery-stellar'));
  // Bootstrap
  gulp.src(config.node_modules + '/bootstrap-sass/assets/javascripts/bootstrap.*')
    .pipe(gulp.dest(config.jsDest + '/bootstrap'));
  gulp.src(config.node_modules + '/smartmenus/dist/**/jquery.*.js')
    .pipe(gulp.dest(config.jsDest + '/smartmenus'));
  // Jasny Bootstrap
  gulp.src(config.node_modules + '/jasny-bootstrap/js/offcanvas.js')
    .pipe(gulp.dest(config.jsDest + '/offcanvas'));
  gulp.src(config.node_modules + '/jasny-bootstrap/js/offcanvas.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.jsDest + '/offcanvas'));
  // Nicescroll.
  gulp.src(config.node_modules + '/jquery.nicescroll/dist/*.{js,png}')
    .pipe(gulp.dest(config.jsDest + '/niceScroll'));
  // jQuery goup.
  gulp.src([
    config.node_modules + '/jquery-goup/src/*.js',
    config.node_modules + '/jquery-goup/*.js'])
    .pipe(gulp.dest(config.jsDest + '/goup'));
  // sass.js - Check
  gulp.src(config.node_modules + '/sass.js/dist/**/*')
    .pipe(gulp.dest(config.jsDest + '/sass-js'));
  // float-labels.js
  gulp.src(config.node_modules + '/float-labels.js/dist/float-labels.min.js')
    .pipe(gulp.dest(config.jsDest + '/float-labels.js'));
  gulp.src(config.node_modules + '/float-labels.js/src/float-labels.js')
    .pipe(gulp.dest(config.jsDest + '/float-labels.js'));
  // Headhesive.
  gulp.src(config.node_modules + '/headhesive/dist/*.js')
    .pipe(gulp.dest(config.jsDest + '/headhesive'));
  // Wowjs - Check
  gulp.src(config.node_modules + '/wowjs/dist/**/*')
    .pipe(gulp.dest(config.jsDest + '/wow'));
  // Color-js
  gulp.src(config.node_modules + '/color-js/color.js')
    .pipe(gulp.dest(config.jsDest + '/color-js'));
  gulp.src(config.node_modules + '/color-js/color.js')
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.jsDest + '/color-js'));
});

// Font Dependencies
gulp.task('fontDeps', function () {
  // Font Awesome
  gulp.src(config.node_modules + '/font-awesome/fonts/**/*.{ttf,woff,woff2,eot,otf,svg}')
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
    '!' + config.node_modules + '/scssphp/tests',
    '!' + config.node_modules + '/scssphp/example/**',
    '!' + config.node_modules + '/scssphp/tests/**',
    config.node_modules + '/scssphp/**/*.php'
  ]).pipe(gulp.dest(config.dist + '/includes/scssphp'));
  // Kirki Customizer Controls.
  gulp.src([
    '!' + config.node_modules + '/kirki-toolkit/assets',
    '!' + config.node_modules + '/kirki-toolkit/assets/**',
    '!' + config.node_modules + '/kirki-toolkit/tests',
    '!' + config.node_modules + '/kirki-toolkit/tests/**',
    '!' + config.node_modules + '/kirki-toolkit/docs',
    '!' + config.node_modules + '/kirki-toolkit/docs/**',
    config.node_modules + '/kirki-toolkit/**',
  ])
    .pipe(replace('kirki-logo.svg', 'boldgrid-logo.svg'))
    .pipe(gulp.dest(config.dist + '/includes/kirki'));
  // Get Kirki CSS.
  gulp.src(config.node_modules + '/kirki-toolkit/assets/**/*.{css,map,json}')
    .pipe(replace('Button styles **/', 'Button styles **', true))
    .pipe(gulp.dest(config.dist + '/includes/kirki/assets'));
  // Get Kirki Assets.
  gulp.src(config.node_modules + '/kirki-toolkit/assets/**/*.{png,scss,js,json}')
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
    config.src + '/**/*.{php,txt,json,css,mo,po,pot}',
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

// Move src svgs to dist.
gulp.task('svgs', function() {
  return gulp.src([config.src + '/assets/img/**/*.svg'])
    .pipe(gulp.dest(config.dist + '/assets/img'))
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

gulp.task( 'webpack', shell.task('npm run build-webpack') );

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
  gulp.src([
	  config.src + '/assets/js/**/*.js',
    '!' + config.src + '/assets/js/customizer/customizer.js',
    '!' + config.src + '/assets/js/customizer/base-customizer.js',
	])
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

	gulp.start('webpack');
});

// Copy SCSS & CSS deps.
gulp.task('scssDeps', function () {
  // Bootstrap
  gulp.src(config.node_modules + '/bootstrap-sass/assets/stylesheets/**/*')
    .pipe(replace(/@import "bootstrap\/buttons";/, '//@import "bootstrap/buttons";'))
    .pipe(replace(/@import "bootstrap\/forms";/, '//@import "bootstrap/forms";'))
    .pipe(replace(/@import "bootstrap\/navbar";/, '//@import "bootstrap/navbar";'))
    .pipe(replace(/@import "bootstrap\/button-groups";/, '//@import "bootstrap/button-groups";'))
    .pipe(replace(/@import "bootstrap\/glyphicons";/, '//@import "bootstrap/glyphicons";'))
    .pipe(gulp.dest(config.dist + '/assets/scss/bootstrap'));
  // Font-Awesome
  gulp.src(config.node_modules + '/font-awesome/scss/**/*.scss')
    .pipe(replace('../fonts', '../../fonts'))
    .pipe(gulp.dest(config.dist + '/assets/scss/font-awesome'));
  // Custom Icons
  gulp.src(config.scss_src + '/icomoon/style.scss')
    .pipe(gulp.dest(config.dist + '/assets/scss/icomoon'));
  // Animate.css
  gulp.src(config.node_modules + '/animate.css/animate.*')
    .pipe(gulp.dest(config.dist + '/assets/css/animate-css'));
  // Underscores
  gulp.src(config.node_modules + '/Buttons/scss/**/*.scss')
    .pipe(replace('$values: #{$values}, #{$i}px #{$i}px #{$kolor};', "$values: unquote(#{$values}+', '+#{$i}+'px '+#{$i}+'px '+#{$kolor});"))
    .pipe(replace("$values: #{$values}, unquote($i * -1 + 'px') #{$i}px #{$kolor};", "$values: unquote(#{$values}+', '+#{$i * -1}+'px '+#{$i}+'px '+#{$kolor});"))
    .pipe(replace("background: linear-gradient(top,", "background: linear-gradient("))
    .pipe(gulp.dest(config.dist + '/assets/scss/buttons'));
  gulp.src(config.node_modules + '/select2-bootstrap-css/select2-bootstrap*.css')
    .pipe(gulp.dest(config.dist + '/assets/css/select2-bootstrap'));

  gulp.src(config.node_modules + '/smartmenus/dist/css/sm-core-css.css')
    .pipe(gulp.dest(config.dist + '/assets/css/smartmenus'));
  gulp.src(config.node_modules + '/smartmenus/dist/addons/**/jquery.*.css')
    .pipe(gulp.dest(config.dist + '/assets/css/smartmenus'));

  // boldgrid-components.
  gulp.src('./node_modules/@boldgrid/components/dist/css/components.*')
    .pipe(gulp.dest(config.dist + '/assets/css'));

  // hamburgers.
  gulp.src(config.node_modules + '/hamburgers/dist/*.css')
    .pipe(gulp.dest(config.dist + '/assets/css/hamburgers'));
  // forms.
  gulp.src(config.node_modules + '/float-labels.js/src/float-labels.scss')
    .pipe(gulp.dest(config.dist + '/assets/scss/float-labels.js'));
});

// Compile SCSS
gulp.task('scssCompile', function () {
  var plugins = [
    require('postcss-flexbugs-fixes'),
    require('autoprefixer')
  ];
  return gulp.src([
    '!' + config.dist + '/assets/scss/bootstrap.scss',
    '!' + config.dist + '/assets/scss/custom-color/**/*',
    config.dist + '/assets/scss/**/*.scss'])
    .pipe(sass({
	 outputStyle: 'expanded',
     includePaths: [
        config.dist + 'assets/scss/',
        config.dist + 'assets/scss/bootstrap',
      ]
    }).on('error', sass.logError))
    .pipe(postcss(plugins))
    .pipe(gulp.dest(config.dist + '/assets/css'))
    .pipe(cssnano({
      safe: true,
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
    .pipe(cssnano({ discardComments: { removeAll: true }, safe: true }))
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest(config.dist + '/assets/css/bootstrap'))
  //  .pipe( notify( { message: 'SCSS compile complete', onLast: true } ) );
});

// Watch for changes and recompile scss
gulp.task('sass:watch', function () {
	gulp.watch( 'src/assets/scss/**/*.scss', function () {
		sequence( 'copyScss', 'scssCompile' );
	} );
} );

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


// WordPress Standard PHP Beautify
gulp.task( 'copyScss', () => {
	return gulp.src( config.src + '/assets/scss/**/*.scss' )
		.pipe( gulp.dest( config.dist + '/assets/scss/' ) );
} );

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

// hovers.
gulp.task('hovers', function() {
  var plugins = [
    require('postcss-hash-classname')({
      hashType: 'md5',
      digestType: 'base32',
      maxLength: 0,
      outputName: 'hover1',
      classnameFormat: '[classname] a',
      type: '.json'
    })
  ];
  gulp.src(config.node_modules + '/hover.css/css/hover*.css')
    .pipe(postcss(plugins))
    .pipe(gulp.dest(config.dist + '/assets/css/hover.css'));
});

gulp.task('hoverColors', function() {
  var plugins = [
    require('postcss-colors-only')({
      withoutGrey: false, // set to true to remove rules that only have grey colors
      withoutMonochrome: false, // set to true to remove rules that only have grey, black, or white colors
      inverse: false, // set to true to remove colors from rules
    }),
    require('postcss-hash-classname')({
      hashType: 'md5',
      digestType: 'base32',
      maxLength: 0,
      outputName: 'hover2',
      classnameFormat: '[classname] a',
      type: '.json'
    }),
    require('postcss-prefix-selector')({ prefix: '%1$s' })
  ];
  return gulp.src(config.node_modules + '/hover.css/css/hover.css')
      .pipe(cssnano({
        safe: true,
        discardComments: { removeAll: true }}))
      .pipe(postcss(plugins))
      .pipe(replace('#fff', '%2$s'))
      .pipe(replace('#2098d1', '%3$s'))
      .pipe(replace('#e1e1e1', '%4$s'))
      .pipe(inject.wrap('<?php \nreturn \'', '\';'))
      .pipe(rename({
        suffix: '-colors-only',
        extname: '.php'
      }))
      .pipe(gulp.dest(config.dist + '/includes/partials'));
});

gulp.task('cleanHovers', function() {
  return gulp.src('hover*.json')
    .pipe(clean({force: true}));
});

// Tasks
gulp.task('build', function (cb) {
  sequence(
    'dist',
    'clean',
    'readme',
    ['jsHint', 'jscs', 'frameworkJs', 'svgs'],
    ['scssDeps', 'jsDeps', 'modernizr', 'fontDeps', 'phpDeps', 'frameworkFiles', 'copyScss', 'translate'],
    'images',
    ['scssCompile', 'bootstrapCompile'],
    'fontFamilyCss',
    'hovers',
    'hoverColors',
    'cleanHovers',
    cb
  );
});

// Tasks
gulp.task('qbuild', function (cb) {
  sequence(
    'dist',
    'readme',
    ['jsHint', 'jscs', 'frameworkJs'],
    ['scssDeps', 'jsDeps', 'modernizr', 'fontDeps', 'phpDeps', 'frameworkFiles', 'copyScss', 'translate'],
    ['scssCompile', 'bootstrapCompile'],
    'fontFamilyCss',
    'hovers',
    'hoverColors',
    'cleanHovers',
    cb
  );
});

gulp.task('framework-js', function (cb) {
  return sequence( [ 'jsHint', 'jscs' ], 'frameworkJs', 'modernizr', cb );
});

gulp.task('prebuild', ['images', 'scssDeps', 'jsDeps', 'fontDeps', 'phpDeps', 'frameworkFiles', 'copyScss', 'translate']);

gulp.task('watch', function () {
	gulp.start( 'sass:watch' );
	gulp.watch(config.src + '/**/*.{php,txt,json,css,mo,po,pot}', ['frameworkFiles', 'translate']);
	gulp.watch(config.src + '/**/*.js', ['framework-js']);
});
