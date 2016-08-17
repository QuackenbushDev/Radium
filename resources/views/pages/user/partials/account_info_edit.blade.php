<div class="row">
    <div class="col-md-6">
        <h3>User Information</h3>
        {!! BootForm::text('Username', 'user["username"]')->value($user->username)->disabled(true) !!}
        {!! BootForm::text('Password', 'user["password"]')->value($user->password) !!}
        {!! BootForm::textArea('Notes', 'userinfo["notes"]') !!}
    </div>

    <div class="col-md-6">
        <h3>Contact Information</h3>
        {!! BootForm::text('Name', 'userinfo["name"]')->value($userInfo->name) !!}
        {!! BootForm::email('E-Mail', 'userinfo["email"]')->value($userInfo->email) !!}
        {!! BootForm::text('Company', 'userinfo["company"]')->value($userInfo->company) !!}
        {!! BootForm::text('Home Phone', 'userinfo["home_phone"]')->value($userInfo->home_phone) !!}
        {!! BootForm::text('Mobile Phone', 'userinfo["mobile_phone"]')->value($userInfo->mobile_phone) !!}
        {!! BootForm::text('Office Phone', 'userinfo["office_phone"]')->value($userInfo->office_phone) !!}
        {!! BootForm::textArea('Address', 'userinfo["address"]')->rows(3)->value($userInfo->address) !!}
        {!! BootForm::checkBox('Enable Portal', 'userinfo["enable_portal"]') !!}
        {!! BootForm::checkBox('Enable self-service password resets', 'userinfo["enable_password_resets"]') !!}
    </div>
</div>
