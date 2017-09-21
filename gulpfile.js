var gulp = require('gulp'),
	gutil = require('gulp-util'),
	argv = require('minimist')(process.argv),
	gulpif = require('gulp-if'),
	prompt = require('gulp-prompt'),
	rsync = require('gulp-rsync'),
	sass = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	cleancss = require('gulp-clean-css'),
	jshint = require('gulp-jshint'),
	uglify = require('gulp-uglify'),
	imagemin = require('gulp-imagemin'),
	rename = require('gulp-rename'),
	concat = require('gulp-concat'),
	notify = require('gulp-notify'),
	livereload = require('gulp-livereload'),
	del = require('del'),
	bust = require('gulp-buster'),
	watch = require('gulp-watch'),
	merge = require('merge-stream'),
	runSequence = require('run-sequence');

// config vars
var busterFile = 'busters.json';
var paths = {
	'in': {
		'vendersass': 'resources/assets/sass/**',
		'mysass': 'resources/assets/sass/app.scss',
		'venderjs': [
			'node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 
			'node_modules/jquery/dist/jquery.min.js',
			'node_modules/photoswipe/dist/photoswipe.min.js',
			'node_modules/photoswipe/dist/photoswipe-ui-default.min.js',
		],
		'myjs': 'resources/assets/js/**',
		'fonts': {
			'myfonts': 'resources/assets/fonts/**',
			'bootstrap': 'node_modules/bootstrap-sass/assets/fonts/bootstrap/**',
			'fontawesome': 'node_modules/font-awesome/fonts/**'
		},
		'buster': ['public/js/**', 'public/css/**']
	},
	'out': {
		'css': 'public/css',
		'js': 'public/js',
		'fonts': {
			'myfonts': 'public/fonts/myfonts',
			'bootstrap': 'public/fonts/bootstrap',
			'fontawesome': 'public/fonts/fontawesome'
		},
		'buster': 'public'
	}
}


// default run all tasks
gulp.task('default', function(cb){
	runSequence('clean',
				'copy',
				'_styles', 
				'_scripts',
				'buster',
				cb);
});



/**
 *
 * Asset copying from venders
 *
 */

gulp.task('copy', ['_copymyfont', '_copybootstrapfont', '_copyfontawesomefont', '_copyvenderjs']);

gulp.task('_copymyfont', function(){
	return gulp.src(paths.in.fonts.myfonts)
			.pipe(gulp.dest(paths.out.fonts.myfonts));
});

gulp.task('_copybootstrapfont', function(){
	return gulp.src(paths.in.fonts.bootstrap)
			.pipe(gulp.dest(paths.out.fonts.bootstrap));
});

gulp.task('_copyfontawesomefont', function(){
	return gulp.src(paths.in.fonts.fontawesome)
			.pipe(gulp.dest(paths.out.fonts.fontawesome));
});

gulp.task('_copyvenderjs', function(){
	return gulp.src(paths.in.venderjs)
			.pipe(gulp.dest(paths.out.js));
});



/**
 *
 * SASS compiling
 *
 */

// All styles, SASS subtask
gulp.task('styles', function(){
	gulp.styleinput = paths.in.vendersass;
	runSequence('_styles');
});

// My styles, SASS subtask
gulp.task('mystyles', function(){
	gulp.styleinput = paths.in.mysass;
	runSequence('_styles');
});

gulp.task('_styles', function(){
	if(!gulp.styleinput){
		gulp.styleinput = [paths.in.mysass, paths.in.vendersass];
	}

	return gulp.src(gulp.styleinput)
			.pipe(sass({
				outputStyle: 'compressed'
			}).on('error', sass.logError))
			.pipe(autoprefixer({
				browsers: ['last 2 versions'],
				cascade: false
			}))
			.pipe(gulp.dest(paths.out.css))
			// .pipe(cleancss()) // handled in sass pipe for now
			// .pipe(gulp.dest(paths.out.css))
			.pipe(notify({
				message: 'Styles compiled!'
			}));
});



/**
 *
 * JS Compiling
 *
 */

gulp.task('myscripts', function(){
	gulp.scriptinput = paths.in.myjs;
	runSequence('_scripts');
});

gulp.task('_scripts', function(){
	if(!gulp.scriptinput){
		gulp.scriptinput = paths.in.myjs;
	}

	return gulp.src(gulp.scriptinput)
			// .pipe(jshint('.jshintrc'))
			// .pipe(jshint.reporter('default'))
			.pipe(concat('app.js'))
			.pipe(gulp.dest(paths.out.js))
			.pipe(rename({suffix: '.min'}))
			.pipe(uglify({
				compressor: {drop_debugger: false}
			}))
			.pipe(gulp.dest(paths.out.js))
			.pipe(notify({
				message: 'Scripts compiled!'
			}));
});



/**
 *
 * Cache Busting
 *
 */

gulp.task('buster', function(){
	return gulp.src(paths.in.buster)
	.pipe(bust({
		'relativePath': paths.out.buster,
		'fileName': busterFile
	}))
	.pipe(gulp.dest(paths.out.buster))
	.pipe(notify({
		message: 'Caches busted!'
	}));
});



/**
 *
 * Watching
 *
 */

gulp.task('watch', function() {
	livereload.listen();

	// Watch .scss files
	gulp.watch(paths.in.vendersass, ['mystyles']);

	// Watch .js files
	gulp.watch(paths.in.myjs, ['myscripts']);

	// Watch image files
	// gulp.watch('src/images/**/*', ['images']);

	gulp.watch(paths.in.buster, ['buster']).on('change', livereload.changed);
});


/**
 *
 * Cleaning
 *
 */

gulp.task('clean', function() {
	return del([paths.out.css, paths.out.js, paths.out.fonts.bootstrap, paths.out.fonts.fontawesome, paths.out.buster+busterFile]);
});

