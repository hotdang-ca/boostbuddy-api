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

    public function saveType(Request $request, $id) {
      $name = $request->name;
      $display_name = $request->display_name;
      $description = $request->description;
      $client_price = $request->client_price;
      $display_order = $request->display_order;

      DB::table('servicetypes')
          ->where('id', $id)
          ->update(
          [
            'name' => $name,
            'display_name' => $display_name,
            'description' => $description,
            'client_price' => $client_price,
            'display_order' => $display_order
          ]
        );

      $result = DB::table('servicetypes')->where('id', $id)->first();
      return response()->json($result);
    }
}
