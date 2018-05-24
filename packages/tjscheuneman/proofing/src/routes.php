<?php

Route::group(['middleware' => 'web'], function () {



//API Functions
    Route::get('proof/project/{id}', 'Tjscheuneman\Proofing\UserProofController@show');
    Route::get('proof/api/info/project/{id}', 'Tjscheuneman\Proofing\ProofAPIController@getProjectData');

    Route::group(['middleware' => ['user']], function () {
        Route::post('proof/api/project/approve', 'Tjscheuneman\Proofing\ProofAPIController@approve');
        Route::post('proof/api/files', 'Tjscheuneman\Proofing\ProofAPIController@userFiles');
        Route::post('/proof/api/project', 'Tjscheuneman\Proofing\UserProofController@store');

    });


    Route::group(['middleware' => ['admin']], function () {
        Route::resource('admin/proof/project', 'Tjscheuneman\Proofing\AdminProofController');

        Route::get('admin/proof/project/add/{id}', 'Tjscheuneman\Proofing\AdminProofController@createRevision');

        Route::post('proof/api/link', 'Tjscheuneman\Proofing\ProofAPIController@getLink');
    });

});


