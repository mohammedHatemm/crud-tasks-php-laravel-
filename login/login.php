<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="login.css" />
</head>

<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php
    if (isset($_GET["message"])) {
      echo "<p class='alert alert-info'>" . htmlspecialchars($_GET["message"]) . "</p>";
    }
    ?>
    <form action="../auth/login_process.php" method="POST">
      <div class="form-group">
        <label for="useremail">Email:</label>
        <input type="email" id="useremail" name="useremail" required />
      </div>
      <div class="form-group">
        <label for="userpassword">Password:</label>
        <input type="password" id="userpassword" name="userpassword" required />
      </div>
      <button type="submit" name="btnLogin" class="submit-btn">Login</button>
    </form>
    <!-- تصحيح رابط صفحة التسجيل -->
    <p>Don't have an account? <a href="../register/register.php">Register here</a></p>
  </div>
</body>

</html>
