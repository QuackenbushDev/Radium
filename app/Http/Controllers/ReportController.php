<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiusAccount;
use App\RadiusPostAuth;

class ReportController extends Controller {
    public function onlineUsers(Request $request) {
        $onlineUserList = RadiusAccount::onlineUsers()
            ->paginate();

        return view()->make(
            'pages.reports.online-users',
            [
                'onlineUserList' => $onlineUserList,
            ]
        );
    }

    public function connectionAttempts(Request $request) {
        $filter = [
            'username'  => $request->input('username', ''),
            'reply'     => (int) $request->input('reply', ''),
            'datestart' => $request->input('datestart', ''),
            'datestop'  => $request->input('datestop', '')
        ];

        $authList = RadiusPostAuth::select(['id', 'username', 'pass', 'reply', 'authdate']);

        if (!empty($filter['username'])) {
            $authList->where('username', $filter['username']);
        }

        if (!empty($filter['reply']) && $filter['reply'] > 0) {
            $reply = ($filter['reply'] === 1) ? 'Access-Accept' : 'Access-Reject';
            $authList->where('reply', $reply);
        }

        if (!empty($filter['datestart'])) {
            $authList->where('authdate', '>=', $filter['datestart']);
        }

        if (!empty($filter['datestop'])) {
            $authList->where('authdate', '<=', $filter['datestop']);
        }

        $authList = $authList->paginate();

        return view()->make(
            'pages.reports.connection-attempts',
            [
                'filter' => $filter,
                'authList' => $authList,
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

    public function topUsers(Request $request) {
        $filter = [
            'nasipaddress'  => $request->input('nasipaddress', ''),
            'acctstarttime' => $request->input('acctstarttime', ''),
            'acctstoptime'  => $request->input('acctstoptime', ''),
        ];
        $userList = RadiusAccount::topUsers(
            $filter['nasipaddress'],
            $filter['acctstarttime'],
            $filter['acctstoptime'],
            25
        )->paginate();

        return view()->make(
            'pages.reports.top-users',
            [
                'userList' => $userList,
                'filter'   => $filter,
            ]
        );
    }
}