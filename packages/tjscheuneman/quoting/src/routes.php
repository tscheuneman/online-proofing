<?php
/**
 * Created by PhpStorm.
 * User: tjscheun
 * Date: 5/14/2018
 * Time: 1:28 PM
 */




Route::group(['middleware' => 'web'], function () {
    Route::get('quotes', 'Tjscheuneman\Quoting\QuotingController@index');


    Route::group(['middleware' => ['admin']], function () {
        Route::get('admin/quotes/builder', 'Tjscheuneman\Quoting\BuildingController@index');
    });

});


