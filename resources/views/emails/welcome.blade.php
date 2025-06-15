
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our App</title>
</head>
<body>
    <h1>Welcome {{ $user->username}}!</h1>
    <p>you login secessfully </p>
    <p>your email <span style="color:blue">{{ $user->email }}</span>  is login</p>

    <p>Thank you for joining our application.</p>
</body>
</html>
