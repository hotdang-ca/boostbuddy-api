<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ServiceProvidersApiController extends Controller
{
    public function addProvider(Request $request) {

      $uuid = uniqid();

      DB::insert(
          'insert into serviceproviders
          (
            name, billing_name, address,
            phone, email, uuid,
            lat, lng, radius
          )
          values
          (
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?
          )',
          [
            $request->name, $request->billing_name, $request->address,
            $request->phone, $request->email, $uuid,
            $request->lat, $request->lng, $request->radius
          ]
      );

      $result = DB::select("SELECT * FROM serviceproviders WHERE uuid = '$uuid' LIMIT 1");
      return response()->json($result[0]);
    }

    public function takeJob(Request $request, $order) {
      // job statuses:
      // 0. Paid -- set when the service is immediately created
      // 1. Looking for best provider -- set when the email goes out to providers
      // 2. Provider acknowledged -- set when a provider accepts the job; eta should be specific at this point
      // 3. Provider arrived -- set when a provider indicates they arrived... optional
      // 4. Provider reported done -- set when the provider marks the job complete
      // 5. Provider reported Gone On Arrival -- set when provider arrives but customer isn't there.
      // 6. Customer cancelled -- set if the customer somehow cancelled the request

      $providers = DB::select("SELECT * FROM serviceproviders WHERE uuid = '$request->provider' LIMIT 1");
      $serviceRequests = DB::select("SELECT * FROM servicerequests WHERE order_number = '$order' LIMIT 1");

      // error_log(count($providers) . ', ' . count($serviceRequests));
      // error_log('we have something...' . print_r($serviceProvider) . ', ' . print_r($serviceRequest));

      $serviceRequest = $serviceRequests[0];
      $serviceProvider = $providers[0];

      if ( isset($serviceRequest) && isset($serviceProvider) ) {
        $status = $serviceRequest->status;
        $statusCode = $serviceRequest->status_code;

        // print_r($serviceRequest);
        // print_r($serviceProvider);

        // just to see who already took it
        $provider = $serviceRequest->service_provider;

        print_r("$status is $statusCode");

        error_log("order $order taken by $serviceProvider->name with ETA of $request->eta");

        if ($statusCode == 1) {
          // it's available
          DB::table('servicerequests')
            ->where('order_number', $order)
            ->update(
            [
              'status_code' => 2,
              'status' => 'Provider acknowledged',
              'service_provider' => $serviceProvider->name,
              'eta' => $request->eta
            ]
          );

          return response()->json(array(
            [
              'status' => 'Provider acknowledged',
              'status_code' => 2
            ]
          ));
        } // other codes handled by change status

      } else { // service requests or provider doesn't exist
        // no jobs match
        error_log('invalid request');

        return response()->json(array([
          'status' => 'Invalid Request',
          'status_code' => -1
        ]));
      }
    }

    public function setJobStatus(Request $request, $order) {
      // 0. Paid -- set when the service is immediately created
      // 1. Looking for best provider -- set when the email goes out to providers
      // 2. Provider acknowledged -- set when a provider accepts the job; eta should be specific at this point
      // 3. Provider arrived -- set when a provider indicates they arrived... optional
      // 4. Provider reported done -- set when the provider marks the job complete
      // 5. Provider reported Gone On Arrival -- set when provider arrives but customer isn't there.
      // 6. Customer cancelled -- set if the customer somehow cancelled the request

    }
}
