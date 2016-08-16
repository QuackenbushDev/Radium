<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use App\RadiusPostAuth;

class DashboardController extends Controller {
    public function index() {
        $dailyStats = RadiusAccount::getConnections('day', date('d'), false)
            ->orderBy('connections', 'desc')
            ->first()
            ->toArray();

        $monthlyStats = RadiusAccount::getConnections('month', date('m'), false)
            ->orderBy('connections', 'desc')
            ->first()
            ->toArray();

        $monthlyBandwidthUsage = RadiusAccount::getMonthlyBandwidthUsage();
        $dailyTopUser   = $this->getTopUser('day', date('d'));
        $monthlyTopUser = $this->getTopUser('month', date('m'));

        $loginAttempts = RadiusPostAuth::getLatestAttempts(5)
            ->get()
            ->toArray();

        return view()->make(
            'pages.dashboard.index',
            [
                'dailyStats'            => $dailyStats,
                'monthlyStats'          => $monthlyStats,
                'dailyTop'              => $dailyTopUser,
                'monthlyTop'            => $monthlyTopUser,
                'loginAttempts'         => $loginAttempts,
                'monthlyBandwidthUsage' => $monthlyBandwidthUsage
            ]
        );
    }

    private function getTopUser($timeframe, $value) {
        $topUser = RadiusAccount::getConnections($timeframe, $value, true)
            ->orderBy('total', 'desc')
            ->first();

        return [
            'username' => $topUser->username,
            'download' => round($topUser->acctinputoctets / 1000000000, 2),
            'upload'   => round($topUser->acctoutputoctets / 1000000000, 2),
        ];
    }
}