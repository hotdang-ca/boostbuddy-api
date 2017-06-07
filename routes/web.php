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
// ->middleware('auth')
Route::group(['prefix' => 'admin'], function () {
  Route::get('/orders', 'AdminController@showAllServiceRequests')->middleware('auth.basic');
  Route::get('/orders/{order}/info/{uuid}', 'AdminController@showServiceRequestInfo');
  Route::get('/serviceTypes', 'AdminController@showAllServiceTypes')->middleware('auth.basic');

  Route::get('/providers', 'ServiceProvidersController@listServiceProviders')->middleware('auth.basic');
  Route::get('/providers/{uuid}/info', 'ServiceProvidersController@providerDetails');
});
