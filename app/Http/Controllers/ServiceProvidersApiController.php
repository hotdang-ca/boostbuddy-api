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
      // 0. Pending -- when it's first created
      // 1. Paid -- set when the service is marked as paid
      // 2. Looking for best provider -- set when the email goes out to providers

      // 3. Provider acknowledged -- set when a provider accepts the job; eta should be specific at this point

      // 4. Provider arrived -- set when a provider indicates they arrived... optional

      // 5. Provider reported done -- set when the provider marks the job complete
      // 6. Provider reported Gone On Arrival -- set when provider arrives but customer isn't there.

      // 7. Customer cancelled -- set if the customer somehow cancelled the request

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

        error_log("order $order taken by $serviceProvider->name with ETA of $request->eta");

        if ($statusCode < 3) {
          // it's available
          DB::table('servicerequests')
            ->where('order_number', $order)
            ->update(
            [
              'status_code' => 3,
              'status' => 'On The Way',
              'service_provider' => $serviceProvider->name,
              'eta' => $request->eta
            ]
          );

          return response()->json(array(
            [
              'status' => 'On The Way',
              'status_code' => 3
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

    public function updateJob(Request $request, $order) {
      // job statuses:
      // 0. Pending -- when it's first created
      // 1. Paid -- set when the service is marked as paid
      // 2. Looking for best provider -- set when the email goes out to providers

      // 3. Provider acknowledged -- set when a provider accepts the job; eta should be specific at this point

      // 4. Provider arrived -- set when a provider indicates they arrived... optional

      // 5. Provider reported done -- set when the provider marks the job complete
      // 6. Provider reported Gone On Arrival -- set when provider arrives but customer isn't there.

      // 7. Customer cancelled -- set if the customer somehow cancelled the request

      $providers = DB::select("SELECT * FROM serviceproviders WHERE uuid = '$request->provider' LIMIT 1");
      $serviceRequests = DB::select("SELECT * FROM servicerequests WHERE order_number = '$order' LIMIT 1");

      $serviceRequest = $serviceRequests[0];
      $serviceProvider = $providers[0];

      if ( isset($serviceRequest) && isset($serviceProvider) ) {
        $status = $serviceRequest->status;
        $statusCode = $serviceRequest->status_code;
        $newStatusCode = $request->code;
        $statusText = "";
        $serviceProvider = $serviceProvider->name;

        $newEarningPotential = $serviceRequest->earning_potential;
        $newCustomerPrice = $serviceRequest->quoted_price;

        if ($newStatusCode == 5) {
          $statusText = "Completed";
        } else if ($newStatusCode == 6) {
          $statusText = "Provider marked Gone On Arrival";
          $newEarningPotential = 25.0;
          $newCustomerPrice = 30.0;

        } else if ($newStatusCode == 7) {
          $statusText = "Provider cancelled the request.";
        } else if ($newStatusCode == 2) {
          // relinquish
          $statusText = "Finding another service provider";
          $serviceProvider = "";
        }

        DB::table('servicerequests')
          ->where('order_number', $order)
          ->update(
          [
            'status_code' => $newStatusCode,
            'status' => $statusText,
            'service_provider' => $serviceProvider,
            'earning_potential' => $newEarningPotential,
            'quoted_price' => $newCustomerPrice
          ]
        );

        return response()->json(array(
          [
            'status_code' => $newStatusCode,
            'status' => $statusText,
          ]
        ));
      }
    }
}
