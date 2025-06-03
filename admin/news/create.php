<?php
// Include database and object files
require_once '../../config/database.php';
require_once '../../models/News.php';
require_once '../../models/Category.php';
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$news = new News($db);
$category = new Category($db);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Set news property values
  $news->name = $_POST['name'];
  $news->content = $_POST['content'];
  $news->user_id = $_SESSION['user_id']; // إضافة معرف المستخدم

  // Set categories if selected
  if (isset($_POST['categories']) && is_array($_POST['categories'])) {
    $news->categories = $_POST['categories'];
  }

  // Create the news
  if ($news->create()) {
    $_SESSION['message'] = "تم إنشاء الخبر بنجاح.";
    $_SESSION['message_type'] = "success";
    header("Location: index.php");
    exit;
  } else {
    $_SESSION['message'] = "فشل في إنشاء الخبر.";
    $_SESSION['message_type'] = "danger";
  }
}

// Include header
include_once '../../includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../../index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="index.php">إدارة الأخبار</a></li>
        <li class="breadcrumb-item active" aria-current="page">إضافة خبر جديد</li>
      </ol>
    </nav>
  </div>
</div>

<?php
// Display messages if any
if (isset($_SESSION['message'])) {
  echo '<div class="alert alert-' . $_SESSION['message_type'] . ' alert-dismissible fade show" role="alert">';
  echo $_SESSION['message'];
  echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
  echo '</div>';
  unset($_SESSION['message']);
  unset($_SESSION['message_type']);
}
?>

<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="mb-0">إضافة خبر جديد</h3>
  </div>
  <div class="card-body">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">عنوان الخبر</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">محتوى الخبر</label>
        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">الفئات</label>
        <div class="card">
          <div class="card-body" style="max-height: 300px; overflow-y: auto;">
            <?php
            // Function to display categories as a tree with checkboxes
            function displayCategoryCheckboxes($category, $parent_id = null, $level = 0)
            {
              if ($parent_id === null) {
                // Get root categories
                $stmt = $category->readRootCategories();
              } else {
                // Set parent ID and get children
                $category->id = $parent_id;
                $stmt = $category->readChildren();
              }

              // Check if any categories exist
              if ($stmt->rowCount() > 0) {
                echo '<ul class="list-unstyled" style="padding-right: ' . ($level * 20) . 'px;">';

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo '<li class="mb-2">';
                  echo '<div class="form-check">';
                  echo '<input class="form-check-input" type="checkbox" name="categories[]" value="' . $row['id'] . '" id="category_' . $row['id'] . '">';
                  echo '<label class="form-check-label" for="category_' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</label>';
                  echo '</div>';

                  // Check if this category has children
                  $category->id = $row['id'];
                  $children = $category->readChildren();
                  if ($children->rowCount() > 0) {
                    displayCategoryCheckboxes($category, $row['id'], $level + 1);
                  }

                  echo '</li>';
                }

                echo '</ul>';
              }
            }

            // Display the category checkboxes
            displayCategoryCheckboxes($category);
            ?>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary">حفظ</button>
        <a href="index.php" class="btn btn-secondary">إلغاء</a>
      </div>
    </form>
  </div>
</div>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
