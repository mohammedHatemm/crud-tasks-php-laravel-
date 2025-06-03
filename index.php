<?php

require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'models/News.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../login/login.php?error=" . urlencode("يجب تسجيل الدخول للوصول إلى هذه الصفحة"));
  exit();
}


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);


include_once 'includes/header.php';
?>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h2 class="mb-0">فئات الأخبار</h2>
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            اختر الفئة
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoryDropdown">
            <?php
            function displayCategoryDropdown($category, $parent_id = null, $level = 0)
            {
              if ($parent_id === null) {
                $stmt = $category->readRootCategories();
              } else {
                $category->id = $parent_id;
                $stmt = $category->readChildren();
              }

              if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $category->id = $row['id'];
                  $children = $category->readChildren();



                  $has_children = $children->rowCount() > 0;


                  $padding = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);

                  if ($has_children) {
                    echo '<li>';

                    echo '<a class="dropdown-item  fw-bold " href="index.php?id='  . $row['id'] . '">'
                      . $padding . htmlspecialchars($row['name']) . '</a>';
                    echo '<li><hr class="dropdown-divider"></li>';
                    displayCategoryDropdown($category, $row['id'], $level + 1);
                    echo '</li>';
                  } else {
                    echo '<li><a class="dropdown-item" href="index.php?id='  . $row['id'] . '">'
                      . $padding  . htmlspecialchars($row['name']) .       '</a></li>';
                  }
                }
              }
            }

            displayCategoryDropdown($category);
            ?>
          </ul>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header bg-info text-white">
        <h2 class="mb-0 text-center">أحدث الأخبار</h2>
      </div>
      <div class="card-body">
        <?php

        $news = new News($db);


        $stmt = $news->read();

        if ($stmt->rowCount() > 0) {
          echo '<div class="row">';

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card h-100">';
            echo '<div class="card-header">' . htmlspecialchars($row['name']) . '</div>';
            echo '<div class="card-body">';
            echo '<p>' . substr(htmlspecialchars($row['content']), 0, 150) . '...</p>';
            echo '</div>';
            echo '<div class="card-footer">';
            echo '<a href="news.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm">قراءة المزيد</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
          }

          echo '</div>';
        } else {
          echo '<div class="alert alert-info">لا توجد أخبار حالياً.</div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php

include_once 'includes/footer.php';
?>
