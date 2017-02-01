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
