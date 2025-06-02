<?php

require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'models/News.php';


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);


include_once 'includes/header.php';
?>

<!-- <div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h2 class="mb-0">فئات الأخبار</h2>
      </div>
      <div class="card-body">
        <div class="folder-container">
          <?php

          function displayCategoryTree($category, $parent_id = null)
          {
            if ($parent_id === null) {

              $stmt = $category->readRootCategories();
            } else {

              $category->id = $parent_id;
              $stmt = $category->readChildren();
            }


            if ($stmt->rowCount() > 0) {
              echo '<ul class="folder-view' . ($parent_id === null ? '' : ' hidden') . '">';

              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $category->id = $row['id'];
                $children = $category->readChildren();
                $has_children = $children->rowCount() > 0;

                echo '<li>';
                if ($has_children) {
                  echo '<div class="folder">' . htmlspecialchars($row['name']) . '</div>';
                  displayCategoryTree($category, $row['id']);
                } else {
                  echo '<a href="category.php?id=' . $row['id'] . '" class="file">' . htmlspecialchars($row['name']) . '</a>';
                }
                echo '</li>';
              }

              echo '</ul>';
            }
          }


          displayCategoryTree($category);
          ?>
        </div>
      </div>
    </div>
  </div>
</div> -->

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
