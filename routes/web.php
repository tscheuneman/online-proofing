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

Route::get('logout.cas', 'LoginController@logoutCas');

Route::get('caslogin', 'LoginController@casLogin');

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

    Route::resource('admin/orders', 'OrderController');

    Route::post('admin/validate/spec', 'ValidatorController@spec');

    Route::get('admin/specs/schema/{id}', 'API\SchemaController@getInfo');

});

Route::group(['middleware' => ['user']], function () {
    Route::get('/', 'HomeController@index');

    Route::get('profile', 'HomeController@userIndex');
    Route::post('profile', 'ProfileController@updateUser');


    Route::post('user/image', 'UserController@image');

});


