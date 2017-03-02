<?php
/* intentionally empty; part of Shift migration */

use Illuminate\Http\Response;

Route::group(['middleware' => 'cors', 'prefix' => 'v0'], function () {
  // Customer Endpoints
    Route::post('/service/request', 'OnboardingController@receiveServiceRequest');
    Route::post('/service/request/{order}/pay', 'OnboardingController@markServiceRequestPaid');
    Route::get('/service/request/{order}/validate', 'OnboardingController@validateRequest');
    Route::get('/service/request/{order}/status', 'OnboardingController@showServiceRequestStatus');
    Route::post('/service/request/{order}/review', 'OnboardingController@reviewServiceRequest');

  // Admin Endpoints
    Route::post('/providers/add', 'ServiceProvidersApiController@addProvider');

  // Provider Endpoints
    Route::post('/service/request/{order}/take', 'ServiceProvidersApiController@takeJob');
    
});
