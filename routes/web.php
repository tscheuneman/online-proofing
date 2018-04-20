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


Route::group(['middleware' => ['admin']], function () {
    /* Admin Routes */

    Route::get('admin/password', 'AdminController@password');
    Route::post('admin/password', 'AdminController@passwordSave');

    Route::resource('admin/customers', 'UserController');
    Route::resource('admin/users', 'AdminController');
    Route::resource('admin/projects', 'ProjectController');
    Route::resource('admin/categories', 'CategoriesController');

    Route::get('admin/search/admin', 'SearchController@findAdmin');
    Route::get('admin/search/users', 'SearchController@findUser');
    Route::get('admin/search/projects', 'SearchController@findProjects');

    Route::get('admin/profile', 'ProfileController@index');
    Route::get('admin', 'AdminIndexController@index');

    Route::post('admin/project/link', 'ProjectActionsController@getLink');
    Route::resource('admin/project', 'ProjectActionsController');

    Route::get('admin/project/add/{id}', 'ProjectActionsController@createRevision');

    Route::resource('admin/orders', 'OrderController');

});

Route::group(['middleware' => ['user']], function () {

    Route::get('password', 'UserController@password');
    Route::post('password', 'UserController@passwordSave');
    Route::get('/', 'HomeController@index');

    Route::post('project', 'UserProjectController@store');


    Route::post('project/approve', 'UserProjectController@approve');

    Route::post('user/files', 'UserProjectController@userFiles');
});





//Auth::routes();


