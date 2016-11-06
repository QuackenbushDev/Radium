<?php namespace App\Http\Controllers;

use App\BandwidthSummary;
use Illuminate\Http\Request;
use App\RadiusAccount;
use App\RadiusPostAuth;
use App\Nas;
use DateTime;

class ReportController extends Controller {
    /**
     * Displays the online user report
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Displays the recent connection attempts.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Generates a bandwidth usage report
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Generates the accounting report for a more details bandwidth summary on
     * a per connection basis
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Displays the top users by bandwidth usage
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function topUsers(Request $request) {
        $filter = [
            'nasName' => $request->input('nasName', ''),
            'start' => $request->input('start', ''),
            'stop'  => $request->input('stop', '')
        ];
        $userList = BandwidthSummary::topUsers(
            $filter['start'],
            $filter['stop'],
            $filter['nasName']
        );

        return view()->make(
            'pages.reports.top-users',
            [
                'userList' => $userList,
                'filter'   => $filter,
            ]
        );
    }
}