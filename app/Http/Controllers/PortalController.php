<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiusCheck;
use App\RadiusAccountInfo;
use App\RadiusPostAuth;

class PortalController extends Controller {
    public function login(Request $request) {
        return view()->make(
            'pages.auth.login-form',
            [
                'action'             => route('portal::doLogin'),
                'forgotPasswordLink' => route('portal::passwordReset'),
                'username'           => session()->get('username', ''),
                'error'              => session()->get('error', '')
            ]
        );
    }

    public function doLogin(Request $request) {
        $user = RadiusCheck::where('username', $request->input('username', null))
            ->first();

        if ($user === null || $user->value !== $request->input('password', null)) {
            session()->flash('username', $request->input('username', ''));
            session()->flash('error', 'Invalid username or password.');

            return redirect(route('portal::login'));
        }

        $userInfo = RadiusAccountInfo::where('username', $user->username)
            ->first();

        if ($userInfo === null || !$userInfo->enable_portal) {
            session()->flash('username', $request->input('username', ''));
            session()->flash('error', 'Portal access not enabled for ' . $user->username);

            return redirect(route('portal::login'));
        }

        session()->put('portal_username', $user->username);
        session()->put('portal_full_name', $userInfo->name);

        return redirect(route('portal::dashboard'));
    }

    public function logout(Request $request) {
        session()->flush();

        return redirect(route('portal::login'));
    }

    public function passwordReset() {

    }

    public function doPasswordReset() {

    }

    public function dashboard() {
        $loginAttempts = RadiusPostAuth::getLatestAttempts(5)
            ->where('username', session()->get('portal_username', ''))
            ->get()
            ->toArray();

        return view()->make(
            'pages.portal.dashboard',
            [
                'loginAttempts' => $loginAttempts,
            ]
        );
    }

    public function profile($username) {

    }

    public function editProfile($username) {

    }

    public function saveProfile($username) {

    }
}