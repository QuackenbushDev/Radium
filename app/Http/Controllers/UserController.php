<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RadiusAccount;
use App\RadiusCheck;
use App\RadiusReply;
use App\RadiusGroupCheck;
use App\RadiusGroupReply;
use App\RadiusPostAuth;
use App\RadiusUserGroup;
use App\Utils\DataHelper;
use App\RadiusAccountInfo;
use App\Nas;

class UserController extends Controller {
    public function index() {
        $users = RadiusCheck::getUserList();
        return view()->make('pages.user.index', ['users' => $users]);
    }

    public function show($id) {
        $user = RadiusCheck::find($id);
        $bandwidthStats = DataHelper::calculateUserBandwidth($user->username);
        $bandwidthMonthlyUsage = RadiusAccount::getMonthlyBandwidthUsage($user->username);
        $onlineStatus = RadiusAccount::onlineStatus($user->username);
        $loginAttempts = RadiusPostAuth::getLatestAttempts('15', $user->username)
            ->get()
            ->toArray();

        $groups = RadiusUserGroup::getUserGroups($user->username);
        $groupCheck = RadiusGroupCheck::whereIn('groupname', $groups)
            ->orderBy('groupname', 'asc')->get()->toArray();
        $groupReply = RadiusGroupReply::whereIn('groupname', $groups)
            ->orderBy('groupname', 'asc')->get()->toArray();

        $userCheck = RadiusCheck::getUserAttributes($user->username)->get()->toArray();
        $userReply = RadiusReply::getUserAttributes($user->username)->get()->toArray();
        $userInfo = RadiusAccountInfo::firstOrCreate(['username' => $user->username]);

        return view()->make(
            'pages.user.show',
            [
                'user'                  => $user,
                'groups'                => $groups,
                'disabledGroupName'     => config('radium.disabled_group'),
                'bandwidthStats'        => $bandwidthStats,
                'bandwidthMonthlyUsage' => $bandwidthMonthlyUsage,
                'onlineStatus'          => $onlineStatus,
                'loginAttempts'         => $loginAttempts,
                'groupCheck'            => $groupCheck,
                'groupReply'            => $groupReply,
                'userCheck'             => $userCheck,
                'userReply'             => $userReply,
                'userInfo'              => $userInfo,
            ]
        );
    }

    public function edit($id) {
        $user = RadiusCheck::find($id);
        $userGroups = RadiusUserGroup::getUserGroups($user->username);
        $groups = array_flatten(RadiusUserGroup::select('groupname')->groupBy('groupname')->get()->toArray());
        $userCheck = RadiusCheck::getUserAttributes($user->username)->get()->toArray();
        $userReply = RadiusReply::getUserAttributes($user->username)->get()->toArray();
        $userInfo = RadiusAccountInfo::firstOrNew(['username' => $user->username]);

        return view()->make(
            'pages.user.edit',
            [
                'user'                  => $user,
                'userGroups'            => $userGroups,
                'groups'                => $groups,
                'userCheck'             => $userCheck,
                'userReply'             => $userReply,
                'userInfo'              => $userInfo,
            ]
        );
    }

    public function store(Request $request, $id) {
        $userRecord = RadiusCheck::find($id);
        $userRecord->value = $request->input('user_password', '');
        $userRecord->save();

        $enablePortal = ($request->input('userinfo_enable_portal', '0') === '1') ? true : false;
        $enablePasswordResets = ($request->input('userinfo_enable_password_resets', '0') === '1') ? true : false;

        $userInfoRecord = RadiusAccountInfo::where('username', '=', $userRecord->username)
            ->update([
                'notes'                  => $request->input('userinfo_notes', ''),
                'name'                   => $request->input('userinfo_name', ''),
                'email'                  => $request->input('userinfo_email', ''),
                'company'                => $request->input('userinfo_company', ''),
                'home_phone'             => $request->input('userinfo_home_phone', ''),
                'office_phone'           => $request->input('userinfo_office_phone', ''),
                'mobile_phone'           => $request->input('userinfo_mobile_phone', ''),
                'address'                => $request->input('userinfo_address'),
                'enable_portal'          => $enablePortal,
                'enable_password_resets' => $enablePasswordResets,
            ]);

        $newGroupList = $request->input('user_groups', []);
        if (!empty($newGroupList)) {
            $userGroups = array_flatten(RadiusUserGroup::getUserGroups($userRecord->username));

            foreach(array_diff($newGroupList, $userGroups) as $group) {
                RadiusUserGroup::create([
                    'username' => $userRecord->username,
                    'groupname' => $group,
                    'priority' => 0
                ]);
            }

            RadiusUserGroup::where('username', $userRecord->username)
                ->whereIn('groupname', array_diff($userGroups, $newGroupList))
                ->delete();
        }

        $request->session()->flash('message', 'Successfully updated user account.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('user::show', $id));
    }

    public function disconnectiFrame($id) {
        $user = RadiusCheck::find($id);
        $nasList = Nas::all();

        return view()->make('pages.user.disconnect-iframe', [
            'user' => $user,
            'nasList' => $nasList,
        ]);
    }

    public function disconnectUser(Request $request) {

    }

    public function disableUser(Request $request, $id) {
        $user = RadiusCheck::find($id);
        $groups = RadiusUserGroup::getUserGroups($user->username);
        $disabledGroupName = config('radium.disabled_group');

        if (in_array($disabledGroupName, $groups)) {
            $request->session()->flash('message', 'User ' . $user->username . ' is already disabled');
            $request->session()->flash('alert-class', 'alert-danger');
        } else {
            $request->session()->flash('message', 'Successfully disabled ' . $user->username);
            $request->session()->flash('alert-class', 'alert-success');

            RadiusUserGroup::create([
                'username'  => $user->username,
                'groupname' => $disabledGroupName,
                'priority'  => 0,
            ]);
        }

        return redirect(route('user::show', $user->id));
    }

    public function enableUser(Request $request, $id) {
        $user = RadiusCheck::find($id);
        $disabledGroupName = config('radium.disabled_group');
        RadiusUserGroup::where('groupname', $disabledGroupName)->delete();

        $request->session()->flash('message', 'Successfully enabled ' . $user->username);
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('user::show', $user->id));
    }
}