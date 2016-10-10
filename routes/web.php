<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

define('DEFAULT_LARAVEL_VERSION', 5.3);

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'laravel', 'namespace'=>'Laravel'], function() {
    Route::get('/', 'DocsController@showRootPage');
    Route::get('/{version}/{page?}', 'DocsController@showPage');
});
