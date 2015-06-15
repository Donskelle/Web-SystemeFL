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

Route::get('/news', 'HomeController@showNews');
Route::get('/help', 'HomeController@showHelp');

Route::get('/settings/profile', 'SettingsController@index');
Route::get('/settings/profile/{username}', 'SettingsController@showProfil');
Route::post('/settings/profile/{username}/save', 'SettingsController@saveProfil');
Route::any('/settings/profile/{username}/fileupload', 'SettingsController@fileupload');

Route::get('/settings/group/{groupId}', 'SettingsController@showGroup');
Route::post('/settings/group/{groupId}/save', 'SettingsController@saveGroup');

Route::get('/settings/document/{documentId}', 'SettingsController@showDocument');
Route::post('/settings/document/{documentId}/save', 'SettingsController@saveDocument');

Route::get('/settings/admin', 'SettingsController@showAdminSettings');
Route::post('/settings/admin/addgroup', 'SettingsController@addNewGroup');

Route::get('/document/new', 'DokuController@newDocu');
Route::post('/document/create', 'DokuController@createDocu');
Route::post('/document/add', 'DokuController@addDocu');
Route::get('/document/{access}/{docuOrGroup}', 'DokuController@param2');
Route::get('/document/{access}/{group}/{docu}', 'DokuController@param3');



Route::any('/document/save', 'DokuController@saveDocu');
Route::any('/document/downloadPDF', 'DokuController@getPDF');
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

Route::any('{a?}/{b?}/{c?}/{d?}/{e?}/{f?}/{g?}', function () {
    return view('errors.404');
});
