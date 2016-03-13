var elixir = require('laravel-elixir');
var gulp = require('gulp');
var del = require('del'); // execute: $ npm install --save-dev del
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
elixir.extend('remove', function(path) {
    new elixir.Task('remove', function() {
      console.log("Deleting... "+path);
      del(path);
    });
});

 elixir(function(mix) {
 	var bpath = 'node_modules/bootstrap-sass/assets';
	var jqueryPath = 'resources/assets/vendor/jquery';
  var jqueryUiPath = 'resources/assets/vendor/jquery-ui';
  var adminltePath = 'resources/assets/vendor/admin-lte';
  var dropzonePath = 'resources/assets/vendor/dropzone';
  var parsleyjsPath = 'resources/assets/vendor/parsleyjs';
  var ioniconsPath = 'resources/assets/vendor/ionicons';
  var fontawesomePath = 'resources/assets/vendor/font-awesome';
  var dataTablesPath = 'resources/assets/vendor/datatables.net';
	mix.sass('app.scss')
    .copy(jqueryPath + '/dist/jquery.min.js', 'public/js')
    .copy(jqueryUiPath + '/jquery-ui.min.js','public/js')
    .copy(adminltePath + '/dist/', 'public/adminlte/dist')
    .copy(adminltePath + '/plugins/', 'public/adminlte/plugins')
 		.copy(bpath + '/fonts', 'public/fonts')
 		.copy(bpath + '/javascripts/bootstrap.min.js', 'public/js')
    .copy(parsleyjsPath + '/dist/parsley.min.js', 'public/js')
    .copy(dropzonePath + '/dist/min/dropzone.min.js', 'public/js')
    .copy(dropzonePath + '/dist/min/dropzone.min.css', 'public/css')
    .copy(fontawesomePath + '/css/font-awesome.min.css', 'public/css')
    .copy(fontawesomePath + '/fonts', 'public/fonts')
    .copy(ioniconsPath + '/css/ionicons.min.css', 'public/css')
    .copy(ioniconsPath + '/fonts', 'public/fonts')
    .copy(dataTablesPath + '/js/jquery.dataTables.min.js', 'public/js');
  mix.remove([ adminltePath + '/plugins/jQuery', 'public/adminlte/plugins/jQuery', adminltePath + '/plugins/jQueryUI' ]);
 });
