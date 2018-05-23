<?php

Route::group(['middleware' => 'web'], function () {

    Route::group(['middleware' => ['admin']], function () {
        //API Functions
        Route::get('logs/api/{id}', 'Tjscheuneman\ActivityEvents\LogsAPIController@getProjectLogs');

    });

});


