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

$app->get('/', function () use ($app) {
  // TODO: redirect
    return 'oh hai!';
});

$app->group(['prefix' => 'admin'], function () use ($app) {
    $app->get('/orders', 'AdminController@showAllServiceRequests');
    $app->get('/orders/{order}/info', 'AdminController@showServiceRequestInfo');
});

$app->group(['prefix' => 'api/v0'], function () use ($app) {
    $app->post('/service/request', 'OnboardingController@receiveServiceRequest');
    $app->post('/service/request/{order}/pay', 'OnboardingController@markServiceRequestPaid');
    $app->get('/service/request/{order}/validate', 'OnboardingController@validateRequest');
    $app->get('/service/request/{order}/status', 'OnboardingController@showServiceRequestStatus');

    $app->post('/service/request/{order}/review', 'OnboardingController@reviewServiceRequest');
});
