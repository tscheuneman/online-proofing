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

    Route::get('admin/search/admin', 'SearchController@findAdmin');
    Route::get('admin/search/users', 'SearchController@findUser');

    Route::get('admin/profile', 'ProfileController@index');
    Route::get('admin', 'AdminIndexController@index');

    //Route::get('admin/project/{id}', 'ProjectActionsController@index');
    Route::resource('admin/project', 'ProjectActionsController');

    Route::resource('admin/orders', 'OrderController');

});

Route::group(['middleware' => ['user']], function () {
    Route::get('/', 'HomeController@index');
    Route::resource('project', 'UserProjectController');
});





Auth::routes();


