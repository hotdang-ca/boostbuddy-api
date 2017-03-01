<?php
/* intentionally empty; part of Shift migration */

use Illuminate\Http\Response;

Route::group(['prefix' => 'v0'], function () {
    Route::post('/service/request', 'OnboardingController@receiveServiceRequest')->middleware('cors');
    Route::post('/service/request/{order}/pay', 'OnboardingController@markServiceRequestPaid')->middleware('cors');
    Route::get('/service/request/{order}/validate', 'OnboardingController@validateRequest');
    Route::get('/service/request/{order}/status', 'OnboardingController@showServiceRequestStatus');
    Route::post('/service/request/{order}/review', 'OnboardingController@reviewServiceRequest')->middleware('cors');

    Route::post('/providers/add', 'ServiceProvidersApiController@addProvider')->middleware('cors');
});
