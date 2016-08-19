<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use App\RadiusPostAuth;
use App\Utils\DataHelper;

class DashboardController extends Controller {
    public function index() {
        $dailyStats = RadiusAccount::getConnections('day', date('d'), false)
            ->orderBy('connections', 'desc')
            ->first();
        $monthlyStats = RadiusAccount::getConnections('month', date('m'), false)
            ->orderBy('connections', 'desc')
            ->first();
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
            ]
        );
    }

    private function getTopUser($timeSpan, $value) {
        $topUser = RadiusAccount::getConnections($timeSpan, $value, true)
            ->orderBy('total', 'desc')
            ->first();

        if ($topUser === null) {
            return [
                'username' => 'N/A',
                'download' => 0,
                'upload'   => 0,
            ];
        }

        return [
            'username' => $topUser->username,
            'download' => DataHelper::convertToHumanReadableSize($topUser->acctinputoctets, 2, 'binary', 3, false),
            'upload'   => DataHelper::convertToHumanReadableSize($topUser->acctoutputoctets, 2, 'binary', 3, false),
        ];
    }
}