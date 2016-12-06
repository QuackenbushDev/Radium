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
use Mail;
use Exception;

class PortalController extends Controller {
    /**
     * Displays the login form for portal users
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Authenticates a user for the portal access
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

    /**
     * Flushes a session to logout a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(Request $request) {
        session()->flush();

        return redirect(route('portal::login'));
    }

    /**
     * Displays password reset form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function passwordReset() {
        return view()->make('pages.portal.forgot-password');
    }

    /**
     * Queues a password reset e-mail if the submitted user exists, and has the ability
     * to reset their own password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doPasswordReset(Request $request) {
        try {
            $accountInfo = RadiusAccountInfo::where('email', $request->input('email'))
                ->where('enable_password_resets', true)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            request()->flash();
            session()->flash('message', 'E-Mail address not found.');
            session()->flash('alert-class', 'error');

            return redirect(route('portal::passwordReset'));
        }

        if ($accountInfo !== null) {
            $accountInfo->reset_password_token = Str::random(30);
            $accountInfo->save();

            $link = route('portal::changePassword', ['token', $accountInfo->reset_password_token]);

            Mail::send(
                'email.portal.password-reset',
                [
                    'title' => 'Password Reset',
                    'name'  => $accountInfo->name,
                    'link'  => $link
                ],
                function($m) use ($accountInfo) {
                    $m->to($accountInfo->email);
                    $m->subject('Password Reset');
                }
            );

            return redirect('portal::passwordResetSuccess');
        } else {
            request()->flash();
            session()->flash('message', 'E-Mail address not found.');
            session()->flash('alert-class', 'error');

            return redirect(route('portal::passwordReset'));
        }
    }

    /**
     * Displays a success message for password reset requests
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function passwordResetSuccess(Request $request) {
        return view()->make('pages.portal.forgot-password-success');
    }

    /**
     * Displays the password change form
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function changePassword(Request $request) {
        $token = $request->input('token', null);
        try {
            if ($token === null) {
                throw new ModelNotFoundException();
            }

            $accountInfo = RadiusAccountInfo::where('reset_password_token', $token)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return redirect(route('portal::resetPassword'));
        }

        return view('pages.portal.change-password', [
            'action'  => route('portal::doChangePassword'),
            'token'   => $token,
            'userID'  => $accountInfo->id,
            'email'   => $accountInfo->email
        ]);
    }


    /**
     * Changes a users password based on their reset token, and redirects the user to the
     * portal.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doChangePassword(Request $request) {
        $token = $request->input('token', null);
        $userID = $request->input('userID');

        try {
            $account = RadiusCheck::findOrFail($userID);
            $accountInfo = RadiusAccountInfo::where('username', $account->username)
                ->where('reset_password_token', $token)
                ->firstOrFail();

            $password = $request->input('password', null);
            $passwordConfirmation = $request->input('passwordConfirmation', null);

            if ($password !== $passwordConfirmation) {
                throw new ModelNotFoundException();
            }
        } catch (ModelNotFoundException $e) {
            session()->flash();
            return redirect(route('portal::changePassword', ['token' => $token]));
        }

        session()->flash('message', 'Password successfully reset!');
        session()->flash('alert-class', 'success');

        $accountInfo->update(['reset_password_token' => null]);
        $account->update(['value' => $password]);

        return redirect(route('portal::login'));
    }

    /**
     * Displays the portal dashboard for a user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard() {
        $bandwidthStats = DataHelper::calculateUserBandwidth(session()->get('portal_username'));
        $loginAttempts = RadiusPostAuth::getLatestAttempts(5)
            ->where('username', session()->get('portal_username', ''))
            ->get()
            ->toArray();

        return view(
            'pages.portal.dashboard',
            [
                'loginAttempts'  => $loginAttempts,
                'bandwidthStats' => $bandwidthStats
            ]
        );
    }

    /**
     * Displays the users profile page.
     *
     * @param Request $request
     * @param $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(Request $request, $username) {
        $user = RadiusCheck::where('username', $username)->first();
        $userInfo = RadiusAccountInfo::where('username', $username)->first();
        $onlineStatus = RadiusAccount::onlineStatus($user->username);
        $groups = RadiusUserGroup::getUserGroups($user->username);

        return view(
            'pages.portal.profile',
            [
                'user'         => $user,
                'userInfo'     => $userInfo,
                'onlineStatus' => $onlineStatus,
                'groups'       => $groups,
            ]
        );
    }

    /**
     * Displays the profile edit form for a user to update their details
     *
     * @param $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile($username) {
        $user = RadiusCheck::where('username', $username)->first();
        $userInfo = RadiusAccountInfo::where('username', $username)->first();

        return view(
            'pages.portal.profile-edit',
            [
                'user'     => $user,
                'userInfo' => $userInfo,
            ]
        );

    }

    /**
     * Handles updating the profile and redircting the user back to the profile view page
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveProfile(Request $request) {
        $userRecord = RadiusCheck::where('username', session()->get('portal_username'))->first();

        $password = $request->input('user_password', null);
        if ($password !== null && !empty($password)) {
            $userRecord->value = $request->input('user_password', '');
            $userRecord->save();
        }

        $enableWeeklySummary = ($request->input('userinfo_enable_weekly_summary', '0') === '1') ? true : false;
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
                'enable_weekly_summary'  => $enableWeeklySummary,
                'enable_monthly_summary' => $enableMonthlySummary,
            ]);

        $request->session()->flash('message', 'Successfully updated user account.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('portal::profile', $userRecord->username));
    }
}