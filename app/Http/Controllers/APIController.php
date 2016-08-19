<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use Illuminate\Http\Request;

class APIController extends Controller {
    /**
     * Generates bandwidth usage for graphs by year, month, and daily summaries.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bandwidthUsage(Request $request) {
        $timeSpan = $request->input('timeSpan', 'month');
        $timeValue = $request->input('timeValue', '');
        $username = $request->input('username', null);
        $nasIP = $request->input('nasIP', null);

        switch ($timeSpan) {
            case "month":
                $headers = array_flatten(cal_info(CAL_GREGORIAN)['months']);
                break;

            case "day":
                $headers = [];
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    $headers[] = (string) $i;
                }
                break;
        }

        $bandwidthUsage = RadiusAccount::bandwidthUsage($timeSpan, $timeValue, $username, $nasIP);

        return response()->json([
            'headers' => $headers,
            'usage'   => $bandwidthUsage,
        ]);
    }
}