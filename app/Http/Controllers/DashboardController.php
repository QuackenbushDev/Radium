<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use App\RadiusPostAuth;
use App\Utils\DataHelper;

class DashboardController extends Controller {
    /**
     * Displays the dashboard for a given user
     *
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Helper function to generate widget information.
     *
     * @param $timeSpan
     * @param $value
     * @return array
     */
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