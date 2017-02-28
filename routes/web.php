<?php

use Illuminate\Http\Response;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::get('/', function () {
  // TODO: redirect
    return 'oh hai!';
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/orders', 'AdminController@showAllServiceRequests');
    Route::get('/orders/{order}/info', 'AdminController@showServiceRequestInfo');
});

Route::group(['prefix' => 'api/v0'], function () {
    Route::post('/service/request', 'OnboardingController@receiveServiceRequest');
    Route::post('/service/request/{order}/pay', 'OnboardingController@markServiceRequestPaid');
    Route::get('/service/request/{order}/validate', 'OnboardingController@validateRequest');
    Route::get('/service/request/{order}/status', 'OnboardingController@showServiceRequestStatus');

    Route::post('/service/request/{order}/review', 'OnboardingController@reviewServiceRequest');
});
