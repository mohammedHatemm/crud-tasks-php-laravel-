<?php
session_start();
require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'models/News.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
  header("Location: login/login.php");
  exit();
}

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$news = new News($db);

// الحصول على الفئة المحددة من الـ URL
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;

$categories = $category->readCategoryHierarchy();

include_once 'includes/header.php';
?>

<div class="container">
  <div class="row mb-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h2 class="mb-0">فئات الأخبار</h2>
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown">
              <?php
              if ($selected_category) {
                $category->id = $selected_category;
                $cat_info = $category->readOne();
                echo htmlspecialchars($cat_info['name']);
              } else {
                echo 'كل الفئات';
              }
              ?>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="index.php">كل الفئات</a></li>
              <?php
              function displayCategories($categories, $level = 0)
              {
                foreach ($categories as $cat) {
                  $padding = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);
                  $class = isset($cat['children']) ? 'fw-bold parent-category' : '';

                  echo '<li><a class="dropdown-item ' . $class . '" href="index.php?category=' . $cat['id'] . '">'
                    . $padding . htmlspecialchars($cat['name']) . '</a></li>';

                  if (isset($cat['children'])) {
                    displayCategories($cat['children'], $level + 1);
                  }
                }
              }

              displayCategories($categories);
              ?>
            </ul>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <?php
            try {
              if ($selected_category) {
                // التحقق من وجود الفئة أولاً
                $category->id = $selected_category;
                $cat_info = $category->readOne();

                if (!$cat_info) {
                  throw new Exception("الفئة غير موجودة");
                }

                $stmt = $news->readByCategory($selected_category);
              } else {
                $stmt = $news->read();
              }

              if ($stmt->rowCount() > 0) {
                // عرض عناصر الأخبار
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo '<div class="col-md-4 mb-4">';
                  echo '<div class="card h-100">';
                  echo '<div class="card-header">' . htmlspecialchars($row['name']) . '</div>';
                  echo '<div class="card-body">';
                  echo '<p class="card-text">' . substr(htmlspecialchars($row['content']), 0, 150) . '...</p>';
                  echo '</div>';
                  echo '<div class="card-footer">';
                  echo '<a href="view_news.php?id=' . $row['id'] . '" class="btn btn-primary">قراءة المزيد</a>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
                }
              } else {
                if ($selected_category) {
                  echo '<div class="alert alert-info">لا توجد أخبار في هذه الفئة</div>';
                } else {
                  echo '<div class="alert alert-info">لا توجد أخبار متاحة</div>';
                }
              }
            } catch (Exception $e) {
              echo '<div class="alert alert-warning">خطأ في عرض الأخبار: ' . $e->getMessage() . '</div>';
              error_log($e->getMessage());
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>
