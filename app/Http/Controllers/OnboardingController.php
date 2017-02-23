<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Stripe\Stripe;
use Stripe\Charge;

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

    public function markServiceRequestPaid(Request $request, $order) {
      $order = '58aa0d82b5a34';

      $pendingOrderSet = DB::select("SELECT * FROM servicerequests WHERE order_number = '$order'");
      // TODO: verify length is at least 0
      $pendingOrder = $pendingOrderSet[0];

      $isPaid = $pendingOrder->isPaid;
      $quotedPrice = $pendingOrder->quoted_price;
      Stripe::setApiKey("sk_test_syNOkivWAVuWiTqUOyVCdlUw");
      $token = $request->stripeToken;


      try {
        $charge = Charge::create(array(
          "amount" => floatval($quotedPrice * 100),
          "currency" => "cad",
          "capture" => false,
          "description" => "Boostbuddy Order $order",
          "source" => $token,
        ));
      } catch (\Stripe\Error\Card $e) {
        error_log($e);
        // TODO: redirect to pay screen, with error text in the GET param
        $errorReason = $e->jsonBody['error']['message'];
        header("Location: http://dev.boostbuddy.ca:3000/payment-details?message=$errorReason");
        exit();
        // return response()->json(array("error" => $errorReason ));
      }

      error_log($charge);

      // is paid?
      $chargeId = $charge['id'];
      $networkStatus = $charge['outcome']['network_status'];
      $chargeType = $charge['outcome']['type'];
      $hasBeenPaid = $charge['paid'];
      $chargeStatus = $charge['status'];

      return response()->json(array(
        "id" => $chargeId,
        "networkStatus" => $chargeStatus,
        "chargeType" => $chargeType,
        "paymentStatus" => $hasBeenPaid,
        "chargeStatus" => $chargeStatus
      ));
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
//      $originDescription = $request->origin['description'];

      // if it's a tow
      if (strcmp($serviceType, "tow") === 0) {
        $destinationLat = $request->destination['lat'];
        $destinationLng = $request->destination['lng'];
        $destinationLabel = $request->destination['label'];
//        $destinationDescription = $request->destination['description'];
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
      $jsonResponse['origin']['description'] = '';
      // TODO: is the lat/lng even in the service area?

      if (strcmp($serviceType, "tow") === 0) {
        $jsonResponse['destination'] = array();
        $jsonResponse['destination']['lat'] = $destinationLat;
        $jsonResponse['destination']['lng'] = $destinationLng;
        $jsonResponse['destination']['label'] = $destinationLabel;
        $jsonResponse['destination']['description'] = '';
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
	$kms = intval($destinationQuotedDistance) / 1000;

        if ($kms > 20) { // its expressed in km
          $difference = kms - 20;
          $price = $price + ($difference * 2.50);
        }
      } else {
        $price = 65;
      }

      $uuid = uniqid();

      // we have everything we need... let's store it.
      if (strcmp($serviceType, 'tow') === 0) {
        DB::insert('insert into servicerequests (
          firstname, lastname, phone, email, car_description, service_type,
          origin_label, origin_desc, origin_lat, origin_lng,
          destination_label, destination_desc, destination_lat, destination_lng, tow_distance,
          quoted_price, order_number, isPaid, created_at, updated_at
        ) values (
          ?, ?, ?, ?, ?, ?,
          ?, ?, ?, ?,
          ?, ?, ?, ?, ?,
          ?, ?, ?, ?, ?)',
        [
          $firstname, $lastname, $phone, $email, $carDescription, $serviceType,
          $originLabel, '', $originLat, $originLng,
          $destinationLabel, '', $destinationLat, $destinationLng, $destinationQuotedDistance,
          $price, $uuid, false, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')
        ]);
      } else {
        DB::insert('insert into servicerequests (
          firstname, lastname, phone, email, car_description, service_type,
          origin_label, origin_desc, origin_lat, origin_lng,
          quoted_price, order_number, isPaid, created_at, updated_at
        ) values (
          ?, ?, ?, ?, ?, ?,
          ?, ?, ?, ?,
          ?, ?, ?, ?, ?)',
        [
          $firstname, $lastname, $phone, $email, $carDescription, $serviceType,
          $originLabel, '', $originLat, $originLng,
          $price, $uuid, false, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')
        ]);
      }

      $results = DB::select("SELECT * FROM servicerequests WHERE order_number = '$uuid'");
      return response()->json($results[0]);
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
