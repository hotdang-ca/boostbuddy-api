<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class OnboardingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->ERROR_404 = ['error' => ['code' => 404, 'description' => 'I have no clue which file you\'re trying to access. So you don\'t get any.']];
      $this->ERROR_400 = ['error' => ['code' => 400, 'description' => 'I have no idea what to do with what you just sent me. Best to just try again, I guess.']];
      $this->ERROR_410 = ['error' => ['code' => 410, 'description' => 'That file is gone. I have no idea where it went. It was here one moment, and then gone the next. Don\'t bother retrying.']];
      $this->ERROR_415 = ['error' => ['code' => 415, 'description' => 'Your file type isn\'t welcome around these parts. Try a different file.']];
      $this->ERROR_413 = ['error' => ['code' => 413, 'description' => 'What are you trying to do?! That file is way too big.']];
      $this->SERVICE_TYPES = ['Boost', 'Tire Change', 'Fuel Delivery', 'Lock-out', 'Tow'];
    }

    public function receiveServiceRequest(Request $request) {
      $firstname = $request->firstname;
      $lastname = $request->lastname;
      $email = $request->email;
      $phone = $request->phone;
      $carDescription = $request->car_description;
      $serviceType = $request->service_type;

      $originLat = $request->origin['lat'];
      $originLng = $request->origin['lng'];
      $originLabel = $request->origin['label'];
      $originDescription = $request->origin['description'];

      // if it's a tow
      if (strcmp($serviceType, "tow") == 0) {
        $destinationLat = $request->destination['lat'];
        $destinationLng = $request->destination['lng'];
        $destinationLabel = $request->destination['label'];
        $destinationDescription = $request->destination['description'];
        $destinationQuotedDistance = $request->destination['quoted_distance'];
      }

      $jsonResponse = array();
      $jsonResponse['firstname'] = $firstname;
      $jsonResponse['lastname'] = $lastname;
      $jsonResponse['email'] = $email;
      $jsonResponse['phone'] = $phone;
      $jsonResponse['carDescription'] = $carDescription;
      $jsonResponse['serviceType'] = $serviceType;

      $jsonResponse['origin'] = array();
      $jsonResponse['origin']['lat'] = $originLat;
      $jsonResponse['origin']['lng'] = $originLng;
      $jsonResponse['origin']['label'] = $originLabel;
      $jsonResponse['origin']['description'] = $originDescription;

      if (strcmp($serviceType, "tow") === 0) {
        $jsonResponse['destination'] = array();
        $jsonResponse['destination']['lat'] = $destinationLat;
        $jsonResponse['destination']['lng'] = $destinationLng;
        $jsonResponse['destination']['label'] = $destinationLabel;
        $jsonResponse['destination']['description'] = $destinationDescription;
        $jsonResponse['destination']['quotedDistance'] = $destinationQuotedDistance;
      }

      // calculate some magic prices
      // Hey Gents I've spent the last 4-5 hours playing around with pricing.
      // I will be doing up a more formalized copy - but for your situational
      // awareness this is the draft:
      // Within the service area Cost to Customer $65 (Boost/Tire Change/Fuel Delivery/Lockout),
      //  provider gets $55, leaving us with $10.
      //  Tows must originate from the service area- Cost to Customer $99 for 20km or less,
      //  $2.50/km beyond,
      //  provider gets $84 and $2.00/km beyond 20km, we get $15 and $0.50/km beyond 20km.
      //
      // Basis is providers earn 85% revenue on flat fees and 80% on variable rates.
      // Providers choose the radius they are willing to service for at these standard
      // price points.
      $price = 0;

      if (strcmp($serviceType, 'tow') === 0) {
        $price = 99;
        if (intval($destinationQuotedDistance) > 20) {
          $difference = intval($destinationQuotedDistance) - 20;
          $price = $price + ($difference * 2.50);
        }
      } else {
        $price = 65;
      }

      $jsonResponse['estimate'] = $price;
      // $jsonResponse['GST'] = $price * 0.05;
      // $jsonResponse['total'] = $price + ( $price * 0.05 );

      // Make us an ORDER!
      

      return response()->json($jsonResponse);
    }

    public function receiveNameAndLocation(Request $request) {
      $jsonResponse = array();

      if (isset($request['name'])) {
        $jsonResponse['name'] = $request->name;
      } else {
        return response()->json($this->ERROR_400, 400);
      }

      if (isset($request['latitude'])) {
        $jsonResponse['lat'] = $request->latitude;
      } else {
        return response()->json($this->ERROR_400, 400);
      }

      if (isset($request['longitude'])) {
        $jsonResponse['lng'] = $request->longitude;
      } else {
        return response()->json($this->ERROR_400, 400);
      }

      // TODO: store in database
      // TODO: dispatch an email event
      // TODO: assign a UUID that the client can use to associate additional fields to this service request
      return response()->json($jsonResponse);
    }

    public function getServiceTypes() {
      return response()->json(["types" => $this->SERVICE_TYPES]); // this may need proper formatting.
    }

    public function receiveServiceType(Request $request) {

    }
}
