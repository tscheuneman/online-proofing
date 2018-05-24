<?php

Route::group(['middleware' => 'web'], function () {

    Route::group(['middleware' => ['admin']], function () {

        Route::post('message/api', 'Tjscheuneman\Messaging\Messaging@store');

        Route::post('message/api/thread', 'Tjscheuneman\Messaging\Messaging@storeThread');
        Route::get('message/api/thread/{id}', 'Tjscheuneman\Messaging\Messaging@showThread');

        Route::get('message/api/{id}', 'Tjscheuneman\Messaging\Messaging@show');

    });

});


