<?php

Route::model('users', 'App\User');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
   Route::get('/users', ['as' => 'admin.users.list', 'uses' => 'Gaia\Users\UserController@index']);
   Route::get('/users/create', ['as' => 'admin.users.create', 'uses' => 'Gaia\Users\UserController@create']);
   Route::get('/users/{users}/edit', ['as' => 'admin.users.edit', 'uses' => 'Gaia\Users\UserController@edit']);
   Route::post('/users/store', ['as' => 'admin.users.store', 'uses' => 'Gaia\Users\UserController@store']);
   Route::post('/users/{users}/update', ['as' => 'admin.users.update', 'uses' => 'Gaia\Users\UserController@update']);
   Route::post('/users/{users}/delete', ['as' => 'admin.users.delete', 'uses' => 'Gaia\Users\UserController@destroy']);
   Route::get('/users/{users}/permissions', ['as' => 'admin.users.permissions', 'uses' => 'Gaia\Users\UserController@permissions']);
   Route::post('/users/{users}/update-permissions', ['as' => 'admin.users.update-permissions', 'uses' => 'Gaia\Users\UserController@updatePermissions']);
});

?>