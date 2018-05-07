<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/







Route::resource('login', 'LoginController');

Route::post('logout', 'LoginController@logout');


Route::get('project/{id}', 'UserProjectController@show');

Route::get('admin/password', 'AdminController@password');
Route::post('admin/password', 'AdminController@passwordSave');

Route::get('password', 'UserController@password');
Route::post('password', 'UserController@passwordSave');


Route::group(['middleware' => ['admin']], function () {
    /* Admin Routes */

    Route::resource('admin/customers', 'UserController');
    Route::resource('admin/users', 'AdminController');
    Route::resource('admin/projects', 'ProjectController');
    Route::resource('admin/categories', 'CategoriesController');
    Route::resource('admin/specifications', 'SpecificationController');
    Route::resource('admin/specifications/schema', 'SpecificationSchemaController');

    Route::get('admin/search/admin', 'SearchController@findAdmin');
    Route::get('admin/search/users', 'SearchController@findUser');
    Route::get('admin/search/projects', 'SearchController@findProjects');

    Route::get('admin/profile', 'ProfileController@index');

    Route::post('admin/profile', 'ProfileController@update');

    Route::get('admin', 'AdminIndexController@index');

    Route::post('admin/project/link', 'ProjectActionsController@getLink');
    Route::resource('admin/project', 'ProjectActionsController');

    Route::get('admin/project/add/{id}', 'ProjectActionsController@createRevision');

    Route::resource('admin/orders', 'OrderController');

    Route::post('admin/message', 'MessageController@store');

    Route::post('admin/message/thread', 'MessageController@storeThread');
    Route::get('admin/message/thread/{id}', 'MessageController@showThread');

    Route::get('admin/message/{id}', 'MessageController@show');

    Route::post('admin/validate/spec', 'ValidatorController@spec');

});

Route::group(['middleware' => ['user']], function () {


    Route::get('/', 'HomeController@index');

    Route::post('project', 'UserProjectController@store');

    Route::get('profile', 'HomeController@userIndex');
    Route::post('profile', 'ProfileController@updateUser');

    Route::post('project/approve', 'UserProjectController@approve');

    Route::post('user/files', 'UserProjectController@userFiles');
    Route::post('user/image', 'UserController@image');

    Route::get('info/project/{id}', 'UserProjectController@getProjectData');
});
Route::get('info/project/{id}', 'UserProjectController@getProjectData');


