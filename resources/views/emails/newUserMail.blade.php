<!DOCTYPE html>
<html>
<head>
    <title>New User</title>
    
</head>
<body>
    <h4>A new user has been created.</h4>
    <h4>Email: {{ $data['email'] }}</h4>
    <h4>Name: {{ $data['name'] }}</h4>
    <h4>Sitename: <a href="{{ env("APP_URL") }}">{{ $setting->company_name }}</a></h4>

    <p>Thank you,</p>
    <h3>{{ env("APP_NAME") }}.</h3>
</body>
</html>
