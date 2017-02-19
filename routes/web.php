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

$app->group(['prefix' => 'api/v0'], function () use ($app) {
  $app->post('/service/request', 'OnboardingController@receiveServiceRequest');
  $app->post('/service/request/paid', 'OnboardingController@markServiceRequestPaid');
});
