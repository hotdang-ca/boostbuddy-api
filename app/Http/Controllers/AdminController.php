<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminController extends Controller
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

    public function authenticate() {

    }

    public function showAllServiceTypes(Request $request) {
      $serviceTypes = DB::table('servicetypes')->get();
      return view('servicetypes', ['types' => $serviceTypes]);
    }

    public function showAllServiceRequests(Request $request) {
      $pendingOrders = DB::table('servicerequests')->orderBy('updated_at', 'desc')->get();
      $providers = DB::table('serviceproviders')->get();
      return view('allorders', ['orders' => $pendingOrders, 'providers' => $providers]);
    }

    public function showServiceRequestInfo(Request $request, $order, $uuid) {
      $provider = new \stdClass();

      if ($uuid !== 'admin') {
        $provider = DB::table('serviceproviders')
                    ->where('uuid', $uuid)
                    ->first();
        if (!isset($provider)) {
          return (":/ Not a provider");
        }
      } else {
        $provider->name = 'admin';
      }

      $pendingOrder = DB::table('servicerequests')
                          ->where('order_number', $order)
                          ->first();

      return view('orderinfo', ['order' => $pendingOrder, 'provider' => $provider]);
    }
}
