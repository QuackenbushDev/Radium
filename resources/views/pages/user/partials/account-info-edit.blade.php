<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <h3>User Information</h3>
                @if (isset($new) && $new)
                    {!! BootForm::text('Username', 'user_username')->value($user->username) !!}
                @else
                    {!! BootForm::text('Username', 'user_username')->value($user->username)->disabled(true) !!}
                @endif

                {!! BootForm::text('Password', 'user_password')->value($user->value) !!}
                <div class="form-group">
                    <label class="control-label" for="user_groups">Groups</label>
                    <select id="user_groups" name="user_groups[]" class="form-control" multiple="multiple">
                        @foreach($groups as $group)
                            @if(in_array($group, $userGroups))
                                <option selected>{{ $group }}</option>
                            @else
                                <option>{{ $group }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {!! BootForm::text('New Group', 'add_user_group_input') !!}
                {!! BootForm::button('Add Group', 'add_user_group_button')->addClass('btn-success') !!}
                <br /><br />

                {!! BootForm::textArea('Notes', 'userinfo_notes')->defaultValue($userInfo->notes) !!}
            </div>

            <div class="col-md-6">
                <h3>Contact Information</h3>
                {!! BootForm::text('Name', 'userinfo_name')->value($userInfo->name) !!}
                {!! BootForm::email('E-Mail', 'userinfo_email')->value($userInfo->email) !!}
                {!! BootForm::text('Company', 'userinfo_company')->value($userInfo->company) !!}
                {!! BootForm::text('Home Phone', 'userinfo_home_phone')->value($userInfo->home_phone) !!}
                {!! BootForm::text('Mobile Phone', 'userinfo_mobile_phone')->value($userInfo->mobile_phone) !!}
                {!! BootForm::text('Office Phone', 'userinfo_office_phone')->value($userInfo->office_phone) !!}
                {!! BootForm::textArea('Address', 'userinfo_address')->rows(3)->value($userInfo->address) !!}
                <!--{!! BootForm::checkBox('Enable Portal', 'userinfo_enable_portal') !!}
                {!! BootForm::checkBox('Enable self-service password resets', 'userinfo_enable_password_resets') !!}-->

                @if ($userInfo->enable_portal)
                    {!! BootForm::checkBox('Enable Portal', 'userinfo_enable_portal')->checked() !!}
                @else
                    {!! BootForm::checkBox('Enable Portal', 'userinfo_enable_portal') !!}
                @endif

                @if ($userInfo->enable_password_resets)
                    {!! BootForm::checkBox('Enable self-service password resets', 'userinfo_enable_password_resets')->checked() !!}
                @else
                    {!! BootForm::checkBox('Enable self-service password resets', 'userinfo_enable_password_resets') !!}
                @endif

                @if ($userInfo->enable_daily_summary)
                    {!! BootForm::checkbox('Enable daily summary', 'userinfo_enable_daily_summary')->checked() !!}
                @else
                    {!! BootForm::checkbox('Enable daily summary', 'userinfo_enable_daily_summary') !!}
                @endif

                @if ($userInfo->enable_monthly_summary)
                    {!! BootForm::checkbox('Enable monthly summary', 'userinfo_enable_monthly_summary')->checked() !!}
                @else
                    {!! BootForm::checkbox('Enable monthly summary', 'userinfo_enable_monthly_summary') !!}
                @endif
            </div>
        </div>
    </div>
</div>
