var gulp = require('gulp');
var elixir = require('laravel-elixir');
var del = require('del');

var paths = {
	'myjs' : 'app.js',
	'jquery': './node_modules/jquery/dist',
	'bootstrap': './node_modules/bootstrap-sass/assets',
	'bootstrap_fonts': 'node_modules/bootstrap-sass/assets/fonts',
	'font_awesome_fonts': 'node_modules/font-awesome/fonts',
	'font_awesome': 'node_modules/font-awesome'
};

var Task = elixir.Task;

elixir.extend("del", function(path) {
	new Task('del', function() {
		return del(path);
	}).watch('./public/build/js/**', 'dels').watch('./public/build/css/**', 'dels');
});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

	// copy files from our node modules to our directory
	mix.copy(paths.bootstrap_fonts + '/bootstrap/**', 'public/build/fonts/bootstrap')
	.copy(paths.font_awesome_fonts + '/**', 'public/build/fonts/font-awesome');

	// sass compiles
	mix.sass('app.scss')
	   .sass('bootstrap.scss')
	   .sass('font-awesome.scss', null, {includePaths: [paths.font_awesome + '/scss']});
	

	// js compiles
	mix.scripts(paths.bootstrap + '/javascripts/bootstrap.min.js')
	   .scripts(paths.jquery + '/jquery.min.js')
	   .scripts(paths.myjs);

	mix.version(['public/js/*.js', 'public/css/*.css'])
	   .del('public/js')
	   .del('public/css');
});

Task.find('version').watch('./public/js/**', ['version']).watch('./public/css/**', ['version']);
