<?php

require_once '../../config/database.php';
require_once '../../models/Category.php';


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $category->name = $_POST['name'];
  $category->description = $_POST['description'];
  $category->parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;


  if ($category->create()) {
    $_SESSION['message'] = "تم إنشاء الفئة بنجاح.";
    $_SESSION['message_type'] = "success";
    header("Location: index.php");
    exit;
  } else {
    $_SESSION['message'] = "فشل في إنشاء الفئة.";
    $_SESSION['message_type'] = "danger";
  }
}


include_once '../../includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-12">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../../index.php">الرئيسية</a></li>
        <li class="breadcrumb-item"><a href="index.php">إدارة الفئات</a></li>
        <li class="breadcrumb-item active" aria-current="page">إضافة فئة جديدة</li>
      </ol>
    </nav>
  </div>
</div>

<?php

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
    <h3 class="mb-0">إضافة فئة جديدة</h3>
  </div>
  <div class="card-body">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">اسم الفئة</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">وصف الفئة</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
      </div>

      <div class="mb-3">
        <label for="parent_id" class="form-label">الفئة الأم</label>
        <select class="form-select" id="parent_id" name="parent_id">
          <option value="">لا يوجد (فئة رئيسية)</option>
          <?php

          $stmt = $category->readForDropdown();

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <button type="submit" class="btn btn-primary">حفظ</button>
        <a href="index.php" class="btn btn-secondary">إلغاء</a>
      </div>
    </form>
  </div>
</div>

<?php

include_once '../../includes/footer.php';
?>
