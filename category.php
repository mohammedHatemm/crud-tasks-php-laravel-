<?php
require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'models/News.php';


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);
$news = new News($db);


$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($category_id <= 0) {
  header("Location: index.php");
  exit;
}


$category->id = $category_id;
$category_exists = $category->readOne();


if (!$category_exists) {
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
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($category->name); ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <h2 class="mb-0"><?php echo htmlspecialchars($category->name); ?></h2>
      </div>
      <div class="card-body">
        <?php if (!empty($category->description)): ?>
          <div class="mb-4">
            <p><?php echo htmlspecialchars($category->description); ?></p>
          </div>
        <?php endif; ?>

        <h3>الأخبار في هذه الفئة</h3>
        <?php

        $stmt = $news->readByCategory($category_id);

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
          echo '<div class="alert alert-info">لا توجد أخبار في هذه الفئة حالياً.</div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

<?php

$category->id = $category_id;
$children = $category->readChildren();

if ($children->rowCount() > 0) {
?>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header bg-info text-white">
          <h2 class="mb-0">الفئات الفرعية</h2>
        </div>
        <div class="card-body">
          <div class="folder-container">
            <ul class="folder-view">
              <?php
              while ($row = $children->fetch(PDO::FETCH_ASSOC)) {
                echo '<li>';
                echo '<a href="category.php?id=' . $row['id'] . '" class="file">' . htmlspecialchars($row['name']) . '</a>';
                echo '</li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
}


include_once 'includes/footer.php';
?>
