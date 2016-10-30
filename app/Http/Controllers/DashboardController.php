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
        $loginAttempts = RadiusPostAuth::getLatestAttempts(5)
            ->get()
            ->toArray();

        return view()->make(
            'pages.dashboard.index',
            [
                'loginAttempts'         => $loginAttempts,
            ]
        );
    }
}