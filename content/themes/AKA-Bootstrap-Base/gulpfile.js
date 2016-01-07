/*

    Gulp config file for AKA Bootstrap theme
    Author: Ash Whiting / Jeremy Basolo / Matt Cegielka @ AKA
    Version: 0.5

*/

// Global File paths

var globalConfig = {
	jsPath:                 'library/js',
	jsPathAll:              'library/js/*.js',
	temp:                   'library/temp',
	scssPath:               'library/scss',
	scssPathAll:            'library/scss/*.scss',
	imgPath:                'library/images',
	destImg:                'build/images/*.{gif,png,jpg,jpeg,svg}',
	dest:                   'build',
	destCss:                'build/css/min',
	destJs:                 'build/js/min'
};

// Add your project scripts here

var jsFileList = [
	globalConfig.jsPath +   '/vendor-libs/modernizr.js', // MUST BE HERE
    globalConfig.jsPath +   '/vendor-libs/utilities/respond.min.js',
    globalConfig.jsPath +   '/vendor-libs/utilities/placeholdr.min.js',
	globalConfig.jsPath +   '/vendor-libs/bootstrap/bootstrap.min.js',
	globalConfig.jsPath +   '/vendor-libs/utilities/orientation-change.js',
    globalConfig.jsPath +   '/scripts.js'
];

// ======================================================================
// !!! Do not edit below this line unless you know what you are doing !!!
// ======================================================================

// Load the Gulp plugins

var gulp =                  require('gulp'); // Load the Gulp core
var runSequence =           require('run-sequence'); // Load as this isn't gulp based
var buster =			    require('gulp-asset-hash'); // Load as this didn't work :P

// Load all the plugins by referring to package.json

var gulpLoadPlugins =       require('gulp-load-plugins');

// Create a namespace for all the plugins

var plugins =               gulpLoadPlugins();


/*

	-------------------------------------------------------------------------------
	Task lists (some as sequences)

 	Sequencing became necessary because we only want to lint scripts.js (not every script!)
	Also, we want to fold Modernizr into the concatenated script file for deployment

	If there is a better solution then fill your boots
	-------------------------------------------------------------------------------

*/

	gulp.task('default', function(){
		runSequence('modernizr', 'lint', 'scripts', 'styles');
	});

	gulp.task('scripts', function(){
		runSequence('modernizr','lint','scripts');
	});

	gulp.task('styles', ['styles']);
	gulp.task('images', ['images']);
	gulp.task('watch', ['watch']);




// Style task

gulp.task('styles', function () {
	return gulp.src([globalConfig.scssPath + '/config.scss']) // Base scss include
		.pipe(plugins.plumber(function(error) {
			errorHandler:reportError
		}))
		.pipe(plugins.sass({
			outputstyle: 'compressed',
		}))
		.on('error', reportError)
		.pipe(plugins.autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
		.pipe(plugins.rename('styles.min.css'))
		.pipe(plugins.bless())
		.pipe(plugins.combineMq())
		.pipe(plugins.minifyCss({
			keepSpecialComments : 0
		}))
		.pipe(gulp.dest(globalConfig.destCss))
		.pipe(buster.hash({
			manifest: 'build/manifest.json',
			template: '<%= name %>.<%= ext %>'
		}))
		.pipe(gulp.dest(globalConfig.destCss))
});

// Scripts task

gulp.task('scripts', function () {
	return gulp.src(
			jsFileList, {
				base: 'library/js/'
			}
		)
		.pipe(plugins.concat({
			path: globalConfig.destJs + '/scripts.js',
			cwd: ''
		}))
		.pipe(plugins.rename('scripts.min.js'))
		.pipe(plugins.uglify())
		.pipe(gulp.dest(globalConfig.destJs))
		.pipe(buster.hash({
			manifest: 'build/manifest.json',
			template: '<%= name %>.<%= ext %>'
		}))
		.pipe(gulp.dest(globalConfig.destJs))
});

// Linting task

gulp.task('lint', function(){
	return gulp.src('library/js/scripts.js')
		.pipe(plugins.jshint())
		.pipe(plugins.plumber(function(error) {
			errorHandler:reportError
		}))
		.pipe(plugins.jshint.reporter('default'))
		.on('error', reportError)
});

// Modernizr task

gulp.task('modernizr', function() {
	return gulp.src("library/js/scripts.js")
		.pipe(plugins.modernizr({
			options: [
				'setClasses',
				'addTest',
				'html5printshiv',
				'testPropsAll',
				'fnBind',
				'domPrefixes'
			]
		}))
		.pipe(gulp.dest(globalConfig.jsPath + '/vendor-libs/'))
});

// Images task (Run optimise tasks);

gulp.task('images', function () {
	return gulp.src(globalConfig.destImages)
		.pipe(plugins.cache(imagemin({
			optimizationLevel: 3,
			progressive: false,
			interlaced: false
		})))
		.pipe(gulp.dest(globalConfig.destImages))
});

// Error reporter function

var reportError = function (error) {
    var lineNumber = (error.lineNumber) ? 'LINE ' + error.lineNumber + ' -- ' : '';

    var report = '';
    var chalk = plugins.util.colors.yellow.bgRed;

    report += chalk('😢 ') + ' [' + error.plugin + ']\n';
    report += chalk('😢 ') + ' ' + error.message + '\n\n';

    if (error.lineNumber) {
		report += chalk('LINE:') + ' ' + error.lineNumber + '\n';
	}

    if (error.fileName) {
		report += chalk('FILE:') + ' ' + error.fileName + '\n';
	}

    console.error(report);
    this.emit('end'); // Stop the watch task from ending
}

gulp.task('watch', function () {
	gulp.watch(globalConfig.scssPathAll, ['styles']);
	gulp.watch(globalConfig.destImg, ['images']);

	// Run the script task in the correct sequence

	gulp.watch(globalConfig.jsPathAll, function() {
		runSequence('modernizr','lint','scripts');
	});

});
