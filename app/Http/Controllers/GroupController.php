<?php namespace App\Http\Controllers;

use App\RadiusCheck;
use Illuminate\Http\Request;
use App\RadiusUserGroup;
use App\RadiusGroupCheck;
use App\RadiusGroupReply;

class GroupController extends Controller {
    public function index(Request $request) {
        $groupList = RadiusUserGroup::selectRaw('groupname, count(groupname) as count')
            ->groupBy('groupname')
            ->paginate();
        $filter = $request->input('filter', '');

        return view()->make(
            'pages.group.index',
            [
                'groupList' => $groupList,
                'filter'    => $filter
            ]
        );
    }

    public function show(Request $request, $groupname) {
        $group = RadiusUserGroup::selectRaw('groupname, count(groupname) as count')
            ->where('groupname', $groupname)
            ->groupBy('groupname')
            ->first();
        $groupUserList = RadiusUserGroup::select(['username'])
            ->where('groupname', $groupname)
            ->get()
            ->toArray();
        $userList = RadiusCheck::getUserList()
            ->whereIn('radcheck.username', $groupUserList)
            ->paginate();
        $check = RadiusGroupCheck::where('groupname', $groupname)
            ->get()
            ->toArray();
        $reply = RadiusGroupReply::where('groupname', $groupname)
            ->get()
            ->toArray();

        return view()->make(
            'pages.group.show',
            [
                'group'    => $group,
                'check'    => $check,
                'reply'    => $reply,
                'userList' => $userList,
            ]
        );
    }

    public function edit(Request $request, $groupname) {
        $group = RadiusUserGroup::selectRaw('groupname, count(groupname) as count')
            ->where('groupname', $groupname)
            ->groupBy('groupname')
            ->first();

        return view()->make(
            'pages.group.edit',
            [
                'group' => $group,
            ]
        );
    }

    public function create() {
        $group = new RadiusUserGroup();

        return view()->make(
            'pages.group.edit',
            [
                'new'   => true,
                'group' => $group,
            ]
        );
    }

    public function save(Request $request) {
        $groupName = $request->input('groupName', '');

        $group = new RadiusUserGroup();
        $group->groupname = $request->input('groupName');
        $group->username  = $request->input('username');
        $group->save();

        $attributes = $request->input('attributes', []);
        foreach(['check', 'reply'] as $type) {
            if (array_key_exists($type, $attributes)) {
                foreach($attributes[$type] as $attribute => $values) {
                    $attributeID = $values['id'];
                    if ($attributeID === '0') {
                        $record = ($type === 'check') ? new RadiusGroupCheck() : new RadiusGroupReply();
                        $record->groupname = $group->groupname;
                    } else {
                        $record = ($type === 'check') ? RadiusGroupCheck::find($attributeID) : RadiusGroupReply::find($attributeID);
                    }

                    $record->attribute = $attribute;
                    $record->op = $values['op'];
                    $record->value = $values['value'];
                    $record->save();
                }
            }
        }

        return redirect(route('group::show', $group->groupname));
    }

    public function store(Request $request, $groupname = null) {
        $attributes = $request->input('attributes', []);
        $deletedAttributes = $request->input('deleted', []);
        foreach(['check', 'reply'] as $type) {
            if (array_key_exists($type, $deletedAttributes)) {
                foreach ($deletedAttributes[$type] as $attributeID) {
                    if ($type === 'check') {
                        RadiusGroupCheck::where('id', $attributeID)
                            ->delete();
                    } else {
                        RadiusGroupReply::where('id', $attributeID)
                            ->delete();
                    }
                }
            }

            if (array_key_exists($type, $attributes)) {
                foreach($attributes[$type] as $attribute => $values) {
                    $attributeID = $values['id'];
                    if ($attributeID === '0') {
                        $record = ($type === 'check') ? new RadiusGroupCheck() : new RadiusGroupReply();
                        $record->groupname = $groupname;
                    } else {
                        $record = ($type === 'check') ? RadiusGroupCheck::find($attributeID) : RadiusGroupReply::find($attributeID);
                    }

                    $record->attribute = $attribute;
                    $record->op = $values['op'];
                    $record->value = $values['value'];
                    $record->save();
                }
            }
        }

        return redirect(route('group::show', $groupname));
    }
}