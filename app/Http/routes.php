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

Route::get('/', 'HomeController@index');
Route::get('/aupload', 'UploadController@index');
Route::get('/aupload/{id}', 'UploadController@index');
Route::get('/aupload/fdelete/{id}', 'UploadController@deleteFile');
Route::post('/aupload', 'UploadController@store');
Route::post('/aupload/{id}', 'UploadController@save');

// images
Route::get('/img/{driver}/{name}', function($driver, $name){
	ini_set('memory_limit','256M');
	$path = Storage::disk($driver)->getDriver()->getAdapter()->getPathPrefix().$name;

	$response = Image::make($path)->response();
	$response->setLastModified(new DateTime("now"));
	$response->setExpires(new DateTime("tomorrow"));
	return $response;
});
