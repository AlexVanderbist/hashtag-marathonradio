<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
    'as' => 'live', 'uses' => 'HomeController@live'
]);

Route::get('/gen', [
    'as' => 'generate', 'uses' => 'DataController@generateWords'
]);

Route::get('/data', [
    'as' => 'data',
	'uses' => 'DataController@getData',
	'middleware' => 'api'
]);

Route::get('/tpm', [
    'as' => 'tpm',
	'uses' => 'DataController@getTweetsPerMinute',
	'middleware' => 'api'
]);
