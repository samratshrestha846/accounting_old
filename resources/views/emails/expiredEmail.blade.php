<!DOCTYPE html>
<html>
<head>
    <title>Expired Mail</title>
</head>
<body>
    @php
        $company = App\Models\Company::latest()->first();
        $super_setting = App\Models\SuperSetting::first();
    @endphp

    <p>Respected <b>{{ $company->name }}</b>,</p>

    <p>Your subscription for LekhaBidhi software is going to expire on <b>{{ date('F j, Y', strtotime($super_setting->expire_date)) }}</b> on <b>{{ date('h:i:s a', strtotime($super_setting->expire_time)) }}</b>.</p>

    <p>Please contact LekhaBidhi for extending your subscription.</p>

    <br>
    <p>Regards,</p>
    <b>{{ env("APP_NAME") }}.</b>
</body>
</html>
