<?php

/*
 * This file was created after migrating from Lumen to Laravel.
 * Therefore, it has some Laravel-specific functionality you will
 * need to pay attention to.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ServiceProvidersController extends Controller
{
    public function listServiceProviders(Request $request) {
      $serviceProviders = DB::table('serviceproviders')->get();
      return view('providers.allproviders', ['providers' => $serviceProviders]);
    }

    public function providerDetails(Request $request, $id) {

    }
}
