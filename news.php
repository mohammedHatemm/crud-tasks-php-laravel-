<?php

require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'models/News.php';


$database = new Database();
$db = $database->getConnection();


$news = new News($db);
$category = new Category($db);


$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($news_id <= 0) {
  header("Location: index.php");
  exit;
}


$news->id = $news_id;
$news_exists = $news->readOne();


if (!$news_exists) {
  header("Location: index.php");
  exit;
}


include_once 'includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
        <?php if (!empty($news->categories) && count($news->categories) > 0): ?>
          <li class="breadcrumb-item">
            <?php

            $category->id = $news->categories[0];
            $category->readOne();
            echo '<a href="category.php?id=' . $category->id . '">' . htmlspecialchars($category->name) . '</a>';
            ?>
          </li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($news->name); ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h2 class="mb-0"><?php echo htmlspecialchars($news->name); ?></h2>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <small class="text-muted">تاريخ النشر: <?php echo date('Y-m-d H:i', strtotime($news->created_at)); ?></small>
        </div>

        <div class="news-content">
          <?php echo nl2br(htmlspecialchars($news->content)); ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-header bg-info text-white">
        <h3 class="mb-0">الفئات</h3>
      </div>
      <div class="card-body">
        <?php
        if (!empty($news->categories)) {
          echo '<ul class="list-group">';
          foreach ($news->categories as $cat_id) {
            $category->id = $cat_id;
            $category->readOne();
            echo '<li class="list-group-item">';
            echo '<a href="category.php?id=' . $category->id . '">' . htmlspecialchars($category->name) . '</a>';
            echo '</li>';
          }
          echo '</ul>';
        } else {
          echo '<div class="alert alert-info">لا توجد فئات مرتبطة بهذا الخبر.</div>';
        }
        ?>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-success text-white">
        <h3 class="mb-0">أخبار ذات صلة</h3>
      </div>
      <div class="card-body">
        <?php

        if (!empty($news->categories)) {
          $related_news = new News($db);
          $stmt = $related_news->readByCategory($news->categories[0]);

          if ($stmt->rowCount() > 0) {
            echo '<ul class="list-group">';
            $count = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

              if ($row['id'] == $news_id) continue;

              echo '<li class="list-group-item">';
              echo '<a href="news.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a>';
              echo '</li>';

              $count++;
              if ($count >= 5) break;
            }

            echo '</ul>';
          } else {
            echo '<div class="alert alert-info">لا توجد أخبار ذات صلة.</div>';
          }
        } else {
          echo '<div class="alert alert-info">لا توجد أخبار ذات صلة.</div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php

include_once 'includes/footer.php';
?>
