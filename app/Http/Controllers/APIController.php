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
        $headers = $this->generateHeaders($timeSpan);

        $bandwidthUsage = RadiusAccount::bandwidthUsage($timeSpan, $timeValue, $username, $nasIP);

        return response()->json([
            'headers' => $headers,
            'usage'   => $bandwidthUsage,
        ]);
    }

    public function connectionCount(Request $request) {
        $timeSpan = $request->input('timeSpan', 'month');
        $timeValue = $request->input('timeValue', '');
        $username = $request->input('username', null);
        $nasIP = $request->input('nasIP', null);
        $headers = $this->generateHeaders($timeSpan);
        $data = [];

        $connections = array_flatten(
            RadiusAccount::connectionCountSummary($timeSpan, $timeValue, $username, $nasIP)
                ->get()
        );

        foreach($connections as $connection) {
            $data[$connection->$timeSpan] = $connection->connections;
        }

        return response()->json([
            'headers' => $headers,
            'dataSet' => $data,
        ]);
    }

    private function generateHeaders($timeSpan) {
        switch ($timeSpan) {
            case "year":
                return array_flatten(
                    RadiusAccount::selectRaw('YEAR(acctstarttime) as year')
                        ->groupBy('year')
                        ->get()
                        ->toArray()
                );

            case "month":
                return array_flatten(cal_info(CAL_GREGORIAN)['months']);

            case "day":
                $headers = [];
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    $headers[] = (string) $i;
                }
                return $headers;
        }

        return [];
    }
}