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
Route::get('/home', 'HomeController@index');
Route::get('/home/{parameter}', 'HomeController@param');

Route::get('/settings/profile', 'SettingsController@index');
Route::get('/settings/profile/{username}', 'SettingsController@showProfil');
Route::post('/settings/profile/{username}/save', 'SettingsController@saveProfil');
Route::any('/settings/profile/{username}/fileupload', 'SettingsController@fileupload');

Route::get('/settings/group/{groupname}', 'SettingsController@showGroup');
Route::get('/settings/group/{groupname}', 'SettingsController@saveGroup');

Route::get('/settings/admin', 'SettingsController@showAdminSettings');
  
Route::get('/dokumete', 'DokuController@index');
Route::get('/dokumete/{access}', 'DokuController@param1');
Route::get('/dokumete/{access}/{dokuOrGroup}', 'DokuController@param2');
Route::get('/dokumete/{access}/{group}/{doku}', 'DokuController@param3');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

