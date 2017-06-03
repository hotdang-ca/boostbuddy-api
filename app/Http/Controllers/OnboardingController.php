<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Mail;

use Stripe\Stripe;
use Stripe\Charge;

use Twilio;

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

    public function showServiceRequestStatus(Request $request, $order) {
      $pendingOrder = DB::table('servicerequests')->where('order_number', $order)->first();
      if (isset($pendingOrder)) {
        return response()->json($pendingOrder);
      } else {
        return response()->json(array());
      }
    }

    public function markServiceRequestPaid(Request $request, $order) {
      $pendingOrder = DB::table('servicerequests')->where('order_number', $order)->first();
      if (isset($pendingOrder)) {

        $isPaid = $pendingOrder->isPaid;
        $quotedPrice = $pendingOrder->quoted_price;

        $TEST_KEY = "***REMOVED***";
        $PROD_KEY = "***REMOVED***";
        $API_KEY = $PROD_KEY;

        Stripe::setApiKey($API_KEY);
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
          header("Location: https://boostbuddy.ca/payment-details?message=$errorReason");
          exit();
          // return response()->json(array("error" => $errorReason ));
        }

//        error_log($charge);

        // is paid?
  //      $chargeId = $charge['id'];
  //      $networkStatus = $charge['outcome']['network_status'];
  //      $chargeType = $charge['outcome']['type'];
        $hasBeenPaid = $charge['paid'];
  //      $chargeStatus = $charge['status'];

        if ($hasBeenPaid) {
          // not paid.. just authorized... but that's still important
          DB::table('servicerequests')
              ->where('order_number', $order)
              ->update(
              [
                'isPaid' => true,
                'status' => 'Paid',
                'status_code' => 2
              ]
            );

          // TODO: send this as a scheduled service, rather than interactively
          // send mail to serviceproviders
          $results = DB::select("SELECT * FROM servicerequests WHERE order_number = '$order'");
          $thisOrder = $results[0];
          $orderNum = $thisOrder->order_number;
          $orderType = $thisOrder->service_type;

          // determine lookup value
          $lookup = "";
          switch ($orderType) {
            case "tow":
              $lookup = "rateTow";
              break;
            case "jump":
              $lookup = "rateBoost";
              break;
            case "tire":
              $lookup = "rateTire";
              break;
            case "fuel";
              $lookup = "rateFuel";
              break;
            case "lockout";
              $lookup = "rateLockout";
              break;
          }

          $providers = DB::select("SELECT * FROM serviceproviders WHERE $lookup > 0");
          foreach ($providers as $provider) {
            $providerUid = $provider->uuid;
            $providerNumber = $provider->phone;
            $twilioMessage = "New Boostbuddy Service Request. To view, click https://api.boostbuddy.ca/admin/orders/$orderNum/info/$providerUid";
            Twilio::message($providerNumber, $twilioMessage);

            Mail::send('providers.emails.newservicerequest', ['provider' => $provider, 'order' => $thisOrder ], function ($m) use ($provider, $thisOrder) {
              $m->from('hello@boostbuddy.ca', 'Boostbuddy Service');
              $m->to($provider->email, $provider->name)->subject('New Boostbuddy Service Request!');
            });
          }
        }

        if (setcookie("boostbuddy-order", $order, strtotime( '+30 days' ), "/", ".boostbuddy.ca", false, false)) {
          header("Location: http://api.boostbuddy.ca/api/v0/service/request/$order/validate");
        } else {
            // no such order
        }

        exit();
      // return response()->json(array(
      //   "id" => $chargeId,
      //   "networkStatus" => $chargeStatus,
      //   "chargeType" => $chargeType,
      //   "paymentStatus" => $hasBeenPaid,
      //   "chargeStatus" => $chargeStatus
      // ));
    }
  }

    public function validateRequest(Request $request)
    {
        $order = $_COOKIE['boostbuddy-order'];
        if (isset($order)) {
            header("Location: https://boostbuddy.ca/status");
            exit();
        } else {
            return response()->json($_COOKIE);
        }
      // some sort of scary error.
    }

    public function receiveServiceRequest(Request $request)
    {
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        $phone = $request->phone;
        $carDescription = $request->car_description;
        $serviceType = $request->service_type;

        $originLat = $request->origin['lat'];
        $originLng = $request->origin['lng'];
        $originLabel = $request->origin['label'];

        $uuid = uniqid();
        $needsWinch = false;
        $needsFlatbed = false;
      // if it's a tow
        if (strcmp($serviceType, 'tow') == 0) {
          $needsWinch = $request->needs_winch;
          $needsFlatbed = $request->needs_flatbed;

          $destinationLat = $request->destination['lat'];
          $destinationLng = $request->destination['lng'];
          $destinationLabel = $request->destination['label'];
          $destinationQuotedDistance = intval(str_replace("," , "", $request->destination['quoted_distance']));
        }

      // // TODO: is the lat/lng even in the service area?

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



// CUSTOMER PRICE
        $price = 65;

        if (strcmp($serviceType, 'tow') == 0) {
            $price += 34;

            $kms = intval($destinationQuotedDistance) / 1000;
            if ($kms > 15) { // its expressed in km
                $difference = $kms - 15;
                $price += ($difference * 3.00);
            }
            if ($needsWinch || $needsFlatbed) {
              $price += 25;
            }

        } else if (strcmp($serviceType, 'fuel') == 0) {
          $price += 10;
        }

        // normalize numbers
        $price = number_format($price, 2);

      // we have everything we need... let's store it.
        if (strcmp($serviceType, 'tow') === 0) {
            DB::insert(
                'insert into servicerequests (
                  firstname, lastname, phone, email, car_description, service_type,
                  origin_label, origin_desc, origin_lat, origin_lng,
                  destination_label, destination_desc, destination_lat, destination_lng, tow_distance,
                  quoted_price, order_number, isPaid, created_at, updated_at, status,
                  needs_winch, needs_flatbed
                ) values (
                  ?, ?, ?, ?, ?, ?,
                  ?, ?, ?, ?,
                  ?, ?, ?, ?, ?,
                  ?, ?, ?, ?, ?, ?,
                  ?, ?)',
                [
                  $firstname, $lastname, $phone, $email, $carDescription, $serviceType,
                  $originLabel, '', $originLat, $originLng,
                  $destinationLabel, '', $destinationLat, $destinationLng, $destinationQuotedDistance,
                  $price, $uuid, false, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 'Pending',
                  $needsWinch, $needsFlatbed
                ]
            );
        } else {
            DB::insert(
                'insert into servicerequests (
                  firstname, lastname, phone, email, car_description, service_type,
                  origin_label, origin_desc, origin_lat, origin_lng,
                  quoted_price, order_number, isPaid, created_at, updated_at, status
                ) values (
                  ?, ?, ?, ?, ?, ?,
                  ?, ?, ?, ?,
                  ?, ?, ?, ?, ?, ?)',
                [
                  $firstname, $lastname, $phone, $email, $carDescription, $serviceType,
                  $originLabel, '', $originLat, $originLng,
                  $price, $uuid, false, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 'Pending'
                ]
            );
        }

        $results = DB::select("SELECT * FROM servicerequests WHERE order_number = '$uuid'");
        $order = $results[0];

        // Notify the admin
        Mail::send('admin.emails.newservicerequest', ['order' => $order ], function ($m) use ($order) {
          $m->from('hello@boostbuddy.ca', 'Boostbuddy Service');
          $m->to("logandd@hotmail.com", "BoostBuddy Admin")->subject('New Boostbuddy Service Request!');
        });

        $orderNum = $order->order_number;
        $providerUid = "admin";
        $twilioMessage = "Hey Admin! New Boostbuddy Service Request. To view, click https://api.boostbuddy.ca/admin/orders/$orderNum/info/$providerUid";
        Twilio::message("204-557-4477", $twilioMessage);

        return response()->json($order);
    }

    public function receiveNameAndLocation(Request $request)
    {
        $jsonResponse = [];

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

    public function getServiceTypes()
    {
        return response()->json(["types" => $this->SERVICE_TYPES]); // this may need proper formatting.
    }

    public function receiveServiceType(Request $request)
    {
    }
}
