<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

// التحقق من طريقة الإرسال
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ../register/register.php?error=invalid_request");
  exit();
}

try {
  // إنشاء اتصال قاعدة البيانات
  $database = new Database();
  $db = $database->getConnection();
  $user = new User($db);

  // استلام البيانات وتنظيفها
  $user->email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $user->password = isset($_POST['password']) ? $_POST['password'] : '';
  $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
  $user->role = isset($_POST['role']) ? $_POST['role'] : 'user';
  $user->phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
  $user->username = isset($_POST['username']) ? trim($_POST['username']) : '';

  // التحقق من صحة البيانات
  $errors = [];

  // التحقق من الحقول المطلوبة
  if (empty($user->username)) {
    $errors[] = "Username is required";
  }

  if (empty($user->email)) {
    $errors[] = "Email is required";
  }

  if (empty($user->password)) {
    $errors[] = "Password is required";
  }

  if (empty($confirm_password)) {
    $errors[] = "Confirm password is required";
  }

  // التحقق من تطابق كلمات المرور
  if ($user->password !== $confirm_password) {
    $errors[] = "Passwords do not match";
  }

  // التحقق من قوة كلمة المرور
  if (strlen($user->password) < 6) {
    $errors[] = "Password must be at least 6 characters long";
  }

  // التحقق من صحة البريد الإلكتروني
  if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
  }

  // التحقق من صحة الدور
  if (!in_array($user->role, ['user', 'admin'])) {
    $errors[] = "Invalid role selected";
  }

  // إذا كانت هناك أخطاء، إعادة التوجيه مع الأخطاء
  if (!empty($errors)) {
    $error_message = implode(", ", $errors);
    header("Location: ../register/register.php?error=" . urlencode($error_message));
    exit();
  }

  // التحقق من وجود البريد الإلكتروني
  if ($user->emailExists()) {
    header("Location: ../register/register.php?error=email_exists");
    exit();
  }

  // التحقق من وجود اسم المستخدم
  if ($user->usernameExists()) {
    header("Location: ../register/register.php?error=username_exists");
    exit();
  }

  // محاولة التسجيل
  if ($user->register()) {
    header("Location: ../login/login.php?message=" .
      urlencode("Registration successful, please login"));
    exit();
  } else {
    header("Location: ../register/register.php?error=" .
      urlencode("Registration failed. Please try again."));
    exit();
  }
} catch (Exception $e) {
  // تسجيل الخطأ (يمكنك إضافة نظام تسجيل أخطاء هنا)
  error_log("Registration error: " . $e->getMessage());

  header("Location: ../register/register.php?error=" .
    urlencode("An error occurred during registration. Please try again."));
  exit();
}
