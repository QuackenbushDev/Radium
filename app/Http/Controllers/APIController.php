<?php namespace App\Http\Controllers;

class APIController extends Controller {
    public function yearlyBandwidthUsage($username = null) {
        $data = [];

        return response()->json($data);
    }
}