<?php

require_once '../../config/database.php';
require_once '../../models/Category.php';


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id <= 0) {
  header("Location: index.php");
  exit;
}


$category->id = $id;
$category_exists = $category->readOne();


if (!$category_exists) {
  $_SESSION['message'] = "الفئة غير موجودة.";
  $_SESSION['message_type'] = "danger";
  header("Location: index.php");
  exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Set category property values
  $category->name = $_POST['name'];
  $category->description = $_POST['description'];
  $category->parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

  // Prevent setting parent to self
  if ($category->parent_id == $category->id) {
    $_SESSION['message'] = "لا يمكن تعيين الفئة كأب لنفسها.";
    $_SESSION['message_type'] = "danger";
  } else {
    // Update the category
    if ($category->update()) {
      $_SESSION['message'] = "تم تحديث الفئة بنجاح.";
      $_SESSION['message_type'] = "success";
      header("Location: index.php");
      exit;
    } else {
      $_SESSION['message'] = "فشل في تحديث الفئة.";
      $_SESSION['message_type'] = "danger";
    }
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
        <li class="breadcrumb-item"><a href="index.php">إدارة الفئات</a></li>
        <li class="breadcrumb-item active" aria-current="page">تحديث الفئة</li>
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
    <h3 class="mb-0">تحديث الفئة</h3>
  </div>
  <div class="card-body">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">اسم الفئة</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($category->name); ?>" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">وصف الفئة</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($category->description); ?></textarea>
      </div>

      <div class="mb-3">
        <label for="parent_id" class="form-label">الفئة الأم</label>
        <select class="form-select" id="parent_id" name="parent_id">
          <option value="">لا يوجد (فئة رئيسية)</option>
          <?php
          // Read all categories for dropdown
          $stmt = $category->readForDropdown();

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Skip current category to prevent self-reference
            if ($row['id'] == $id) continue;

            $selected = ($row['id'] == $category->parent_id) ? 'selected' : '';
            echo "<option value='{$row['id']}' {$selected}>" . htmlspecialchars($row['name']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
        <a href="index.php" class="btn btn-secondary">إلغاء</a>
      </div>
    </form>
  </div>
</div>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
