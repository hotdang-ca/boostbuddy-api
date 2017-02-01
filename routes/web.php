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
  // Step 1
  // Receive users' first and last name, lat/lng
  $app->post('/service/step1',  'OnboardingController@receiveNameAndLocation');

  // Step 2:
  // Receive users' service required (one of Boost, Tire Change, Fuel Delivery, Lock-out or Tow)
  $app->get('/service/types',   'OnboardingController@getServiceTypes');
  $app->post('/service/step2',  'OnboardingController@receiveServiceType');

  // Step 3:
  // Receive payment details... oh man...
  $app->post('/service/step3',  'OnboardingController@receivePaymentDetails');

  // Step 4:
  // Offer to create an account to save all these pieces of data
  $app->post('/service/step4',  'OnboardingController@receiveAccountDetails');


});
