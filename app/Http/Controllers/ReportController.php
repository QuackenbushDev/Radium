<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiusAccount;

class ReportController extends Controller {
    public function onlineUsers(Request $request) {
        return view()->make(
            'pages.reports.online-users',
            [

            ]
        );
    }

    public function connectionAttempts(Request $request) {
        return view()->make(
            'pages.reports.connection-attempts',
            [

            ]
        );
    }

    public function bandwidth(Request $request) {
        $filter = [
            'username'        => $request->input('username', ''),
            'nasipaddress'    => $request->input('nasipaddress', ''),
            'acctstarttime'   => $request->input('acctstarttime', ''),
            'acctstoptime'    => $request->input('acctstoptime', '')
        ];

        return view()->make(
            'pages.reports.bandwidth',
            [
                'filter' => $filter,
            ]
        );
    }

    public function accounting(Request $request) {
        $columns = [
            'radacctid',
            'radacct.username',
            'acctsessiontime',
            'acctstarttime',
            'acctstoptime',
            'acctinputoctets',
            'acctoutputoctets',
            'acctterminatecause',
            'nasipaddress',
            'framedipaddress'
        ];
        $filter = [
            'username'        => $request->input('username', ''),
            'framedipaddress' => $request->input('framedipaddress', ''),
            'nasipaddress'    => $request->input('nasipaddress', ''),
            'acctstarttime'   => $request->input('acctstarttime', ''),
            'acctstoptime'    => $request->input('acctstoptime', '')
        ];
        $radiusAccounting = RadiusAccount::select($columns);

        foreach($filter as $key => $value) {
            if (!empty($value)) {
                if ($key === 'acctstarttime' || $key === 'acctstoptime') {
                    $dateTimeObject = new DateTime($value);
                    $queryOperator = ($key === 'acctstarttime') ? '>=' : '<=';

                    $radiusAccounting->where($key, $queryOperator, $dateTimeObject);
                } else {
                    $radiusAccounting->where($key, 'LIKE', '%' . $value . '%');
                }
            }
        }

        $dataSet = $radiusAccounting->paginate()->appends($filter);
        return view()->make(
            'pages.reports.accounting',
            [
                'accountingList' => $dataSet,
                'filter' => $filter
            ]
        );
    }
}