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
	merge = require('merge-stream');
	// runSequence = require('run-sequence');

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
	gulp.series('clean',
				'copy',
				'_styles', 
				'_scripts',
				'buster')();
	cb();
});



/**
 *
 * Asset copying from venders
 *
 */


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

gulp.task('copy', gulp.series('_copymyfont', '_copybootstrapfont', '_copyfontawesomefont', '_copyvenderjs'));

/**
 *
 * SASS compiling
 *
 */

// All styles, SASS subtask
gulp.task('styles', function(dn){
	gulp.styleinput = paths.in.vendersass;
	gulp.series('_styles')();
	dn();
});

// My styles, SASS subtask
gulp.task('mystyles', function(dn){
	gulp.styleinput = paths.in.mysass;
	gulp.series('_styles')();
	dn();
});

gulp.task('_styles', function(dn){
	if(!gulp.styleinput){
		gulp.styleinput = [paths.in.mysass, paths.in.vendersass];
	}

	gulp.src(gulp.styleinput)
			.pipe(sass({
				outputStyle: 'compressed'
			}).on('error', sass.logError))
			.pipe(autoprefixer({
				cascade: false
			}))
			.pipe(gulp.dest(paths.out.css))
			// .pipe(cleancss()) // handled in sass pipe for now
			// .pipe(gulp.dest(paths.out.css))
			.pipe(notify({
				message: 'Styles compiled!'
			}));
	dn();
});



/**
 *
 * JS Compiling
 *
 */

gulp.task('myscripts', function(dn){
	gulp.scriptinput = paths.in.myjs;
	gulp.series('_scripts')();
	dn();
});

gulp.task('_scripts', function(dn){
	if(!gulp.scriptinput){
		gulp.scriptinput = paths.in.myjs;
	}

	gulp.src(gulp.scriptinput)
			// .pipe(jshint('.jshintrc'))
			// .pipe(jshint.reporter('default'))
			.pipe(concat('app.js'))
			.pipe(gulp.dest(paths.out.js))
			.pipe(rename({suffix: '.min'}))
			.pipe(uglify())
			.pipe(gulp.dest(paths.out.js))
			.pipe(notify({
				message: 'Scripts compiled!'
			}));
	dn();
});



/**
 *
 * Cache Busting
 *
 */

gulp.task('buster', function(dn){
	gulp.src(paths.in.buster)
	.pipe(bust({
		'relativePath': paths.out.buster,
		'fileName': busterFile
	}))
	.pipe(gulp.dest(paths.out.buster))
	.pipe(notify({
		message: 'Caches busted!'
	}));
	dn();
});



/**
 *
 * Watching
 *
 */

gulp.task('watch', function() {
	livereload.listen();

	// Watch .scss files
	gulp.watch(paths.in.vendersass, gulp.series('mystyles'));

	// Watch .js files
	gulp.watch(paths.in.myjs, gulp.series('myscripts'));

	// Watch image files
	// gulp.watch('src/images/**/*', ['images']);

	gulp.watch(paths.in.buster, gulp.series('buster')).on('change', livereload.changed);
});


/**
 *
 * Cleaning
 *
 */

gulp.task('clean', function() {
	return del([paths.out.css, paths.out.js, paths.out.fonts.bootstrap, paths.out.fonts.fontawesome, paths.out.buster+busterFile]);
});

