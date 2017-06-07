<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ResourcesApiController extends Controller
{
    public function listTypes(Request $request) {
      $serviceTypes = DB::table('servicetypes')->get();
      if (isset($serviceTypes)) {
        return response()->json($serviceTypes);
      } else {
        return response()->json(array());
      }
    }
}
