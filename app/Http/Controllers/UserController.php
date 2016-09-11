<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
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
    /**
     * User listing
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request) {
        $filterValue = $request->input('filter', '');
        $users = RadiusCheck::getUserList();

        if (!empty($filterValue)) {
            $users->where('radcheck.username', 'LIKE', '%' . $filterValue . '%');
        }

        $users = $users->paginate();

        return view()->make(
            'pages.user.index',
            [
                'users' => $users,
                'filterValue' => $filterValue,
            ]
        );
    }

    /**
     * Displays a users record
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $user = RadiusCheck::find($id);
        $bandwidthStats = DataHelper::calculateUserBandwidth($user->username);
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

    /**
     * Displays the edit form for a user.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id) {
        $user = RadiusCheck::find($id);
        $userGroups = RadiusUserGroup::getUserGroups($user->username);
        $groups = array_flatten(RadiusUserGroup::select('groupname')->groupBy('groupname')->get()->toArray());
        $userInfo = RadiusAccountInfo::firstOrNew(['username' => $user->username]);

        return view()->make(
            'pages.user.edit',
            [
                'user'                  => $user,
                'userGroups'            => $userGroups,
                'groups'                => $groups,
                'userInfo'              => $userInfo,
            ]
        );
    }

    /**
     * Displays an empty edit for to create a new user record.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $user = new RadiusCheck();
        $groups = array_flatten(RadiusUserGroup::select('groupname')->groupBy('groupname')->get()->toArray());
        $userInfo = new RadiusAccountInfo();

        return view()->make(
            'pages.user.edit',
            [
                'user'                  => $user,
                'userGroups'            => [],
                'groups'                => $groups,
                'userInfo'              => $userInfo,
                'new'                   => true,
            ]
        );
    }

    /**
     * Creates a new user record with the submitted information
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request) {
        $user = new RadiusCheck();
        $user->username = $request->input('user_username', '');
        $user->attribute = 'Cleartext-Password';
        $user->op = ':=';
        $user->value = $request->input('user_password');
        $user->save();

        $enablePortal = ($request->input('userinfo_enable_portal', '0') === '1') ? true : false;
        $enablePasswordResets = ($request->input('userinfo_enable_password_resets', '0') === '1') ? true : false;
        $enableWeeklySummary = ($request->input('userinfo_enable_weekly_summary', '0') === '1') ? true : false;
        $enableMonthlySummary = ($request->input('userinfo_enable_monthly_summary', '0') === '1') ? true : false;

        $userInfoRecord = RadiusAccountInfo::create([
                'username'               => $user->username,
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
                'enable_weekly_summary'  => $enableWeeklySummary,
                'enable_monthly_summary' => $enableMonthlySummary,
            ]);

        foreach($request->input('user_groups', []) as $group) {
            RadiusUserGroup::create([
                'username' => $user->username,
                'groupname' => $group,
                'priority' => 0
            ]);
        }

        $request->session()->flash('message', 'Successfully created new user!');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('user::show', $user->id));
    }

    /**
     * Updates a user record with the submitted information
     *
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $id = null) {
        $userRecord = RadiusCheck::find($id);
        $userRecord->value = $request->input('user_password', '');
        $userRecord->save();

        $enablePortal = ($request->input('userinfo_enable_portal', '0') === '1') ? true : false;
        $enablePasswordResets = ($request->input('userinfo_enable_password_resets', '0') === '1') ? true : false;
        $enableWeeklySummary = ($request->input('userinfo_enable_weekly_summary', '0') === '1') ? true : false;
        $enableMonthlySummary = ($request->input('userinfo_enable_monthly_summary', '0') === '1') ? true : false;

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
                'enable_weekly_summary'  => $enableWeeklySummary,
                'enable_monthly_summary' => $enableMonthlySummary,
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

        $attributes = $request->input('attributes', []);
        $deletedAttributes = $request->input('deleted', []);
        foreach(['check', 'reply'] as $type) {
            if (array_key_exists($type, $deletedAttributes)) {
                foreach ($deletedAttributes[$type] as $attributeID) {
                    if ($type === 'check') {
                        RadiusCheck::where('id', $attributeID)
                            ->delete();
                    } else {
                        RadiusReply::where('id', $attributeID)
                            ->delete();
                    }
                }
            }

            if (array_key_exists($type, $attributes)) {
                foreach($attributes[$type] as $attribute => $values) {
                    $attributeID = $values['id'];
                    if ($attributeID === '0') {
                        $record = ($type === 'check') ? new RadiusCheck() : new RadiusReply();
                    } else {
                        $record = ($type === 'check') ? RadiusCheck::find($attributeID) : RadiusReply::find($attributeID);
                    }

                    $record->username = $userRecord->username;
                    $record->attribute = $attribute;
                    $record->op = $values['op'];
                    $record->value = $values['value'];
                    $record->save();
                }
            }
        }

        $request->session()->flash('message', 'Successfully updated user account.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('user::show', $id));
    }


    /**
     * Renders the disconnect iFrame
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function disconnectiFrame($id) {
        $user = RadiusCheck::find($id);
        $nasList = [];

        foreach (Nas::all() as $nas) {
            $nasList[$nas->id] = $nas->shortname . ' (' . $nas->nasname . ')';
        }

        return view()->make('pages.user.disconnect-form', [
            'user' => $user,
            'nasList' => $nasList,
        ]);
    }

    /**
     * Runs the disconnect command to disconnect a given user
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function disconnectUser(Request $request, $id) {
        $user = RadiusCheck::find($id);
        $nas  = Nas::find($request->input('nas_id'));

        $attributes = $request->input('attributes');
        $attributes = str_replace("\r\n", "\n", $attributes);
        $attributes = explode("\n", $attributes);
        $attributeLine = implode(',', $attributes);

        $echo = "echo \"User-Name='". $user->username . "'";
        if (!empty($attributeLine)) {
            $echo .= ',' . $attributeLine . '"';
        } else {
            $echo .= '"';
        }

        $radClient = "radclient -c '1' -n '3' -r '3' -t '3' -x '"
            . $nas->nasname . ":" . $request->input('nas_port') . "' '"
            . $request->input('packet_type', 'disconnect') . "' '" . $nas->secret . "'";

        $command = $echo . " | " . $radClient;

        try {
            $process = new Process($command);
            $process->mustRun();

            $output = $process->getOutput();
        } catch (ProcessFailedException $e) {
            $output = $e->getMessage();
        }

        return view()->make(
            'pages.user.disconnect-results',
            [
                'user'    => $user,
                'command' => $command,
                'output'  => $output,
            ]
        );
    }

    /**
     * Displays the testing iFrame to verify a user account is working
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function testiFrame(Request $request, $id) {
        $user = RadiusCheck::find($id);

        return view()->make(
            'pages.user.test-form',
            [
                'user'         => $user,
                'radiusServer' => config('radium.radius_server'),
                'radiusPort'   => config('radium.radius_port'),
                'radiusSecret' => config('radium.radius_secret'),
                'nasPort'      => config('radium.nas_port'),
            ]
        );
    }

    /**
     * Runs the test command to verify a user account is working
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function testUser(Request $request, $id) {
        $user = RadiusCheck::find($id);
        $radiusServer = $request->input('radius_server', '') . ':' . $request->input('radius_port', '0');
        $radiusSecret = $request->input('radius_secret');

        $echo = "echo \"User-Name='". $user->username . "',User-Password='" . $user->value . "'\"";
        $radClient = "radclient -c '1' -n '3' -r '3' -t '3' -x '" . $radiusServer . "' 'auth' '" . $radiusSecret . "'";
        $command = $echo . " | " . $radClient;

        try {
            $process = new Process($command);
            $process->mustRun();

            $output = $process->getOutput();
        } catch (ProcessFailedException $e) {
            $output = $e->getMessage();
        }

        return view()->make(
            'pages.user.test-results',
            [
                'user'    => $user,
                'command' => $command,
                'output'  => $output,
            ]
        );
    }

    /**
     * Disables a user account
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

    /**
     * Reenable a user account
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enableUser(Request $request, $id) {
        $user = RadiusCheck::find($id);
        $disabledGroupName = config('radium.disabled_group');
        RadiusUserGroup::where('groupname', $disabledGroupName)->delete();

        $request->session()->flash('message', 'Successfully enabled ' . $user->username);
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('user::show', $user->id));
    }

    /**
     * Hashes a users password with the selected hashing mechanism
     *
     * @param $passwordType
     * @param $password
     * @return string
     */
    private function hashPassword($passwordType, $password) {
        switch($passwordType) {
            case 'Crypt-Password':
                return crypt($password, Str::random());

            default:
            case 'Cleartext-Password':
                return $password;
        }
    }
}