<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use App\RadiusCheck;
use App\RadiusReply;
use App\RadiusGroupCheck;
use App\RadiusGroupReply;
use App\RadiusPostAuth;
use App\RadiusUserGroup;
use App\Utils\DataHelper;
use App\RadiusAccountInfo;

class UserController extends Controller {
    public function index() {
        $users = RadiusCheck::getUserList();

        return view()->make(
            'pages.user.index',
            [
                'users' => $users
            ]
        );
    }

    public function show($id) {
        $user = RadiusCheck::find($id)
            ->join('radius_account_info', 'radcheck.username', '=', 'radius_account_info.username')
            ->first();
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

        return view()->make(
            'pages.user.show',
            [
                'user'                  => $user,
                'groups'                => $groups,
                'bandwidthStats'        => $bandwidthStats,
                'bandwidthMonthlyUsage' => $bandwidthMonthlyUsage,
                'onlineStatus'          => $onlineStatus,
                'loginAttempts'         => $loginAttempts,
                'groupCheck'            => $groupCheck,
                'groupReply'            => $groupReply,
                'userCheck'             => $userCheck,
                'userReply'             => $userReply,
            ]
        );
    }
}