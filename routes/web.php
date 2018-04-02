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

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/password', 'AdminController@password');
Route::post('admin/password', 'AdminController@passwordSave');

Route::group(['middleware' => ['admin']], function () {
    /* Admin Routes */
    Route::resource('admin/customers', 'UserController');
    Route::resource('admin/users', 'AdminController');
    Route::resource('admin/projects', 'ProjectController');
    Route::resource('admin/categories', 'CategoriesController');

    Route::get('admin/search/admin', 'SearchController@findAdmin');
    Route::get('admin/search/users', 'SearchController@findUser');

});




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
