<?php

Route::group(['middleware' => 'web'], function () {

    Route::get('proof/project/{id}', 'Tjscheuneman\Proofing\UserProofController@show');

//API Functions
    Route::get('proof/api/info/project/{id}', 'Tjscheuneman\Proofing\ProofAPIController@getProjectData');

    Route::group(['middleware' => ['user']], function () {
        Route::post('proof/api/project/approve', 'Tjscheuneman\Proofing\ProofAPIController@approve');

    });


    Route::group(['middleware' => ['admin']], function () {
        Route::resource('admin/proof/project', 'Tjscheuneman\Proofing\AdminProofController');

        Route::get('admin/proof/project/add/{id}', 'Tjscheuneman\Proofing\AdminProofController@createRevision');
    });

});


