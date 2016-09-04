<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use App\RadiusUserGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\RadiusCheck;
use App\RadiusAccountInfo;
use App\RadiusPostAuth;
use App\Utils\DataHelper;
use Illuminate\Support\Str;

class PortalController extends Controller {
    public function login(Request $request) {
        return view()->make(
            'pages.portal.login',
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
        return view()->make('pages.portal.forgot-password');
    }

    public function doPasswordReset(Request $request) {
        $email = $request->input('email', null);

        if ($email !== null) {
            $user = RadiusAccountInfo::where('email', $email)->firstOrFail();
            $user->reset_password_token = Str::random(30);
            $user->save();

            Mail::send('', function($m) use ($user) {

            });

            // redirect stuff
        } else {
            session()->flash();
        }
    }

    public function changePassword(Request $request, $resetToken) {

    }

    public function doChangePassword(Request $request) {

    }

    public function dashboard() {
        $bandwidthStats = DataHelper::calculateUserBandwidth(session()->get('portal_username'));
        $loginAttempts = RadiusPostAuth::getLatestAttempts(5)
            ->where('username', session()->get('portal_username', ''))
            ->get()
            ->toArray();

        return view()->make(
            'pages.portal.dashboard',
            [
                'loginAttempts'  => $loginAttempts,
                'bandwidthStats' => $bandwidthStats
            ]
        );
    }

    public function profile(Request $request, $username) {
        $user = RadiusAccount::where('username', $username)->first();
        $userInfo = RadiusAccountInfo::where('username', $username)->first();
        $onlineStatus = RadiusAccount::onlineStatus($user->username);
        $groups = RadiusUserGroup::getUserGroups($user->username);

        return view()->make(
            'pages.portal.profile',
            [
                'user'         => $user,
                'userInfo'     => $userInfo,
                'onlineStatus' => $onlineStatus,
                'groups'       => $groups,
            ]
        );
    }

    public function editProfile($username) {
        $user = RadiusAccount::where('username', $username)->first();
        $userInfo = RadiusAccountInfo::where('username', $username)->first();

        return view()->make(
            'pages.portal.profile-edit',
            [
                'user'     => $user,
                'userInfo' => $userInfo,
            ]
        );

    }

    public function saveProfile(Request $request) {
        $userRecord = RadiusCheck::where('username', session()->get('portal_username'))->first();

        $password = $request->input('user_password', null);
        if ($password !== null) {
            $userRecord->value = $request->input('user_password', '');
            $userRecord->save();
        }

        $enableDailySummary = ($request->input('userinfo_enable_daily_summary', '0') === '1') ? true : false;
        $enableMonthlySummary = ($request->input('userinfo_enable_monthly_summary', '0') === '1') ? true : false;
        RadiusAccountInfo::where('username', '=', $userRecord->username)
            ->update([
                'notes'                  => $request->input('userinfo_notes', ''),
                'name'                   => $request->input('userinfo_name', ''),
                'email'                  => $request->input('userinfo_email', ''),
                'company'                => $request->input('userinfo_company', ''),
                'home_phone'             => $request->input('userinfo_home_phone', ''),
                'office_phone'           => $request->input('userinfo_office_phone', ''),
                'mobile_phone'           => $request->input('userinfo_mobile_phone', ''),
                'address'                => $request->input('userinfo_address'),
                'enable_daily_summary'   => $enableDailySummary,
                'enable_monthly_summary' => $enableMonthlySummary,
            ]);

        $request->session()->flash('message', 'Successfully updated user account.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('portal::profile', $userRecord->username));
    }
}