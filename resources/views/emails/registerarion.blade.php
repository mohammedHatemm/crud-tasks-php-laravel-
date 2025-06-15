
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our App</title>
</head>
<body>
    <h1>Welcome {{ $user->username}}!</h1>
    <p>you sign up secssfully .</p>
    <p>your mail <span style="color:blue">{{ $user->email }}</span>  is registeration</p>

    <p>Thank you for joining our application.</p>
</body>
</html>
