<?php

session_start();

require_once '../config/database.php';
require_once '../models/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../login/login.php?error=invalid_request");
  exit();
}
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$user->email = isset($_POST['useremail']) ? $_POST['useremail'] : '';
$password_input = isset($_POST['userpassword']) ? $_POST['userpassword'] : '';

if ($user->emailExists()) {
  if (password_verify($password_input, $user->password)) {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['role'] = $user->role;
    if ($user->role == 'admin') {
      $_SESSION['is_admin'] = true;
      header("Location: ../admin/categories/index.php");
    } elseif ($user->role == 'user') {
      header("Location: ../index.php");
    }
    exit;
  } else {
    header("Location: ../login/login.php?error=invalid_credentials");
    exit();
  }
} else {
  header("Location: ../login/login.php?error=email_not_found");
  exit();
}
