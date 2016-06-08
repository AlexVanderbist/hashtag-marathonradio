var elixir = require('laravel-elixir');

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

	mix.copy('node_modules/numeral/min/numeral.min.js', 'resources/assets/js/');
	mix.copy('node_modules/jquery.animate-number/jquery.animateNumber.min.js', 'resources/assets/js/');
	mix.copy('node_modules/moment/min/moment-with-locales.min.js', 'resources/assets/js/');
	mix.copy('node_modules/chart.js/dist/Chart.min.js', 'resources/assets/js/');
	mix.copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'resources/assets/js/');
	mix.copy('node_modules/bootstrap-sass/assets/fonts/', 'public/fonts/');
	mix.copy('node_modules/jquery/dist/jquery.min.js', 'resources/assets/js/');
    mix.scripts(['numeral.min.js','jquery.min.js','moment-with-locales.min.js', 'jquery.animateNumber.min.js', 'Chart.min.js','bootstrap.min.js'], 'public/js/vendor.js');
    mix.sass('app.scss');

});
