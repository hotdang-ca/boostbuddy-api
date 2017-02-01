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

    }

    public function receiveNameAndLocation(Request $request) {

      try {
        $name = $request->name;
        $lat = $request->latitude;
        $lng = $request->longitude;
      } catch (Exception $e) {
        return response()->json($this->ERROR_400, 400);
      }

      $jsonResponse = array();
      $jsonResponse->name = $name;
      $jsonResponse->lat = $lat;
      $jsonResponse->lng = $lng;

      return response()->json($jsonResponse);
    }

    public function getServiceTypes() {
      $serviceTypes = ['Boost', 'Tire Change', 'Fuel Delivery', 'Lock-out', 'Tow'];
      return response()->json(["types" => $serviceTypes]); // this may need proper formatting.
    }

    public function receiveServiceType(Request $request) {

    }
}
