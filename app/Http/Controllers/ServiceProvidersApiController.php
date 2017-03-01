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
}
