<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="register.css">
</head>

<body>
  <div class="login-container">
    <h2>Register</h2>

    <?php
    // عرض رسائل النجاح
    if (isset($_GET["message"])) {
      echo "<div class='alert alert-success'>" . htmlspecialchars($_GET["message"]) . "</div>";
    }

    // عرض رسائل الخطأ
    if (isset($_GET["error"])) {
      $error = $_GET["error"];
      $error_message = "";

      switch ($error) {
        case "password_mismatch":
          $error_message = "Passwords do not match";
          break;
        case "email_exists":
          $error_message = "Email already exists. Please use a different email.";
          break;
        case "username_exists":
          $error_message = "Username already exists. Please choose a different username.";
          break;
        case "invalid_request":
          $error_message = "Invalid request method";
          break;
        default:
          $error_message = htmlspecialchars($error);
          break;
      }

      echo "<div class='alert alert-danger'>" . $error_message . "</div>";
    }
    ?>

    <form action="../auth/register_process.php" method="POST" id="registerForm">
      <div class="form-group mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text"
          id="username"
          name="username"
          class="form-control"
          value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>"
          required>
      </div>

      <div class="form-group mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email"
          id="email"
          name="email"
          class="form-control"
          value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>"
          required>
      </div>

      <div class="form-group mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password"
          id="password"
          name="password"
          class="form-control"
          minlength="6"
          required>
        <small class="form-text text-muted">Password must be at least 6 characters long</small>
      </div>

      <div class="form-group mb-3">
        <label for="confirm_password" class="form-label">Confirm Password:</label>
        <input type="password"
          id="confirm_password"
          name="confirm_password"
          class="form-control"
          required>
        <div id="passwordError" class="text-danger" style="display: none;">Passwords do not match</div>
      </div>

      <div class="form-group mb-3">
        <label for="phone" class="form-label">Phone:</label>
        <input type="tel"
          id="phone"
          name="phone"
          class="form-control"
          value="<?php echo isset($_GET['phone']) ? htmlspecialchars($_GET['phone']) : ''; ?>">
      </div>

      <div class="form-group mb-3">
        <label for="role" class="form-label">Role:</label>
        <select name="role" id="role" class="form-control" required>
          <option value="user" <?php echo (!isset($_GET['role']) || $_GET['role'] == 'user') ? 'selected' : ''; ?>>User</option>
          <option value="admin" <?php echo (isset($_GET['role']) && $_GET['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary submit-btn w-100" id="submitBtn">Register</button>
    </form>

    <p class="mt-3 text-center">
      Already have an account?
      <a href="../login/login.php">Login here</a>
    </p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm_password');
      const passwordError = document.getElementById('passwordError');
      const submitBtn = document.getElementById('submitBtn');
      const form = document.getElementById('registerForm');

      function checkPasswordMatch() {
        if (password.value && confirmPassword.value) {
          if (password.value !== confirmPassword.value) {
            passwordError.style.display = 'block';
            confirmPassword.classList.add('is-invalid');
            return false;
          } else {
            passwordError.style.display = 'none';
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
            return true;
          }
        }
        return true;
      }

      password.addEventListener('input', checkPasswordMatch);
      confirmPassword.addEventListener('input', checkPasswordMatch);

      form.addEventListener('submit', function(e) {
        if (!checkPasswordMatch()) {
          e.preventDefault();
          return false;
        }
      });
    });
  </script>
</body>

</html>
