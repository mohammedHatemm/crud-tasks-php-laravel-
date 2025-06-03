<?php
$base_url = "http://localhost/";

// بدء الجلسة إذا لم تكن قد بدأت بالفعل
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق مما إذا كان المستخدم مسجل الدخول
$is_logged_in = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام إدارة الأخبار</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
  <style>
    .folder-view {
      list-style-type: none;
      padding-left: 20px;
    }

    .folder-view li {
      margin: 10px 0;
    }

    .folder-view .folder {
      cursor: pointer;
    }

    .folder-view .folder:before {
      content: "📁 ";
    }

    .folder-view .folder.open:before {
      content: "📂 ";
    }

    .folder-view .file:before {
      content: "📄 ";
    }

    .folder-view .hidden {
      display: none;
    }
  </style>
</head>

<body>
  <header class="bg-dark text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h1><a href="" class="text-white text-decoration-none">نظام إدارة الأخبار</a></h1>
        </div>
        <div class="col-md-6 text-end">
          <nav>
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo $base_url; ?>/index.php">الرئيسية</a>
              </li>

              <?php if ($is_logged_in): ?>
                <?php if ($is_admin): ?>
                  <!-- روابط المشرف (Admin) -->
                  <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url ?>/admin/categories/index.php">إدارة الفئات</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url ?>/admin/news/index.php">إدارة الأخبار</a>
                  </li>
                <?php else: ?>
                  <!-- روابط المستخدم العادي (User) -->
                  <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $base_url ?>/admin/news/index.php">أخباري</a>
                  </li>
                <?php endif; ?>

                <!-- رابط تسجيل الخروج لجميع المستخدمين المسجلين -->
                <li class="nav-item">
                  <a class="nav-link text-white" href="<?php echo $base_url ?>/auth/logout.php">تسجيل الخروج</a>
                </li>
              <?php else: ?>
                <!-- روابط للزوار غير المسجلين -->
                <li class="nav-item">
                  <a class="nav-link text-white" href="<?php echo $base_url ?>/login/login.php">تسجيل الدخول</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="<?php echo $base_url ?>/register/register.php">إنشاء حساب</a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>
  <main class="container py-4">
