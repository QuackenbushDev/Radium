<?php namespace App\Http\Controllers;

use App\Dictionary;
use App\RadiusAccount;
use App\RadiusCheck;
use App\RadiusGroupCheck;
use App\RadiusGroupReply;
use App\RadiusReply;
use Illuminate\Http\Request;
use Storage;

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

        if (session()->has('portal_username', '')) {
            $username = session()->get('portal_username');
        }

        $bandwidthUsage = RadiusAccount::bandwidthUsage($timeSpan, $timeValue, $username, $nasIP);

        return response()->json([
            'headers' => $headers,
            'usage'   => $bandwidthUsage,
        ]);
    }

    /**
     * Returns an array of headers and connection counts for the specified time span
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function connectionCount(Request $request) {
        $timeSpan = $request->input('timeSpan', 'month');
        $timeValue = $request->input('timeValue', '');
        $username = $request->input('username', null);
        $nasIP = $request->input('nasIP', null);
        $headers = $this->generateHeaders($timeSpan);

        if (session()->has('portal_username', '')) {
            $username = session()->get('portal_username');
        }

        $data = RadiusAccount::connectionCountSummary($timeSpan, $timeValue, $username, $nasIP);

        return response()->json([
            'headers' => $headers,
            'dataSet' => $data,
        ]);
    }

    /**
     * returns an array with the vendor attributes.
     * Response format: [
     *     "vendor" => $vendor,
     *     "attribute" => $attribute,
     *     "op" => $op
     * ]
     *
     * @param Request $request
     * @return string
     */
    public function attributes(Request $request) {
        $username = $request->input('username', '');
        $groupName = $request->input('groupName', '');
        $type = $request->input('type');

        if (!empty($username)) {
            if ($type === 'check') {
                $data = RadiusCheck::getUserAttributes($username)
                    ->get()
                    ->toArray();
            } else {
                $data = RadiusReply::getUserAttributes($username)
                    ->get()
                    ->toArray();
            }
        } elseif(!empty($groupName)) {
            if ($type === 'check') {
                $data = RadiusGroupCheck::where('groupname', $groupName)
                    ->get()
                    ->toArray();
            } else {
                $data = RadiusGroupReply::where('groupname', $groupName)
                    ->get()
                    ->toArray();
            }
        } else {
            return response()->json('missing_username_or_group_name');
        }

        return response()->json($data);
    }

    public function dictionaryVersion() {
        return response(Storage::get('dictionaryVersion.txt'));
    }

    /**
     * returns a list of vendor attributes found in the dictionary
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vendorAttributes(Request $request) {
        $vendors = [];
        $dictionary = [];

        foreach(Dictionary::all() as $entry) {
            if (!array_key_exists($entry->vendor, $dictionary)) {
                $vendors[] = $entry->vendor;
                $dictionary[$entry->vendor] = [];
            }

            $dictionary[$entry->vendor][$entry->attribute] = [
                'attribute' => $entry->attribute,
                //'type' => $entry->attribute_type,
                //'length' => $entry->length,
                'values' => json_decode($entry->values),
            ];
        }

        return response()->json([
            'vendors' => $vendors,
            'dictionary' => $dictionary,
        ]);
    }

    /**
     * Returns an array of years (where data is available), months,
     * or a list with the number of days starting from 1
     *
     * @param $timeSpan
     * @return array
     */
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