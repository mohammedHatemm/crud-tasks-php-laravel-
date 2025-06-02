<?php

require_once '../../config/database.php';
require_once '../../models/Category.php';


$database = new Database();
$db = $database->getConnection();


$category = new Category($db);


if (isset($_GET['delete']) && !empty($_GET['delete'])) {
  $category->id = $_GET['delete'];
  if ($category->delete()) {
    $_SESSION['message'] = "تم حذف الفئة بنجاح.";
    $_SESSION['message_type'] = "success";
  } else {
    $_SESSION['message'] = "فشل في حذف الفئة.";
    $_SESSION['message_type'] = "danger";
  }
  header("Location: index.php");
  exit;
}


include_once '../../includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-6">
    <h2>إدارة الفئات</h2>
  </div>
  <div class="col-md-6 text-end">
    <a href="create.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> إضافة فئة جديدة
    </a>
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
    <h3 class="mb-0">قائمة الفئات</h3>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>الاسم</th>
            <th>الوصف</th>
            <th>الفئة الأم</th>
            <th>تاريخ الإنشاء</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Read all categories
          $stmt = $category->read();

          if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['description']) . "</td>";
              echo "<td>" . ($row['parent_id'] ? htmlspecialchars($row['parent_name']) : 'لا يوجد') . "</td>";
              echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
              echo "<td>";
              echo "<a href='view.php?id={$row['id']}' class='btn btn-info btn-sm btn-action'><i class='fas fa-eye'></i></a>";
              echo "<a href='update.php?id={$row['id']}' class='btn btn-primary btn-sm btn-action'><i class='fas fa-edit'></i></a>";
              echo "<a href='#' onclick='confirmDelete({$row['id']})' class='btn btn-danger btn-sm btn-action'><i class='fas fa-trash'></i></a>";
              echo "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>لا توجد فئات.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card mt-4">
  <div class="card-header bg-info text-white">
    <h3 class="mb-0">عرض الفئات كمجلدات</h3>
  </div>
  <div class="card-body">
    <div class="folder-container">
      <?php
      // Function to display categories as a folder structure
      function displayCategoryTree($category, $parent_id = null)
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
          echo '<ul class="folder-view' . ($parent_id === null ? '' : ' hidden') . '">';

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Check if this category has children
            $category->id = $row['id'];
            $children = $category->readChildren();
            $has_children = $children->rowCount() > 0;

            echo '<li>';
            if ($has_children) {
              echo '<div class="folder">' . htmlspecialchars($row['name']) . '</div>';
              displayCategoryTree($category, $row['id']);
            } else {
              echo '<a href="view.php?id=' . $row['id'] . '" class="file">' . htmlspecialchars($row['name']) . '</a>';
            }
            echo '</li>';
          }

          echo '</ul>';
        }
      }

      // Display the category tree
      displayCategoryTree($category);
      ?>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">تأكيد الحذف</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        هل أنت متأكد من حذف هذه الفئة؟ سيتم إزالة جميع الارتباطات بالأخبار.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">حذف</a>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDelete(id) {
    document.getElementById('confirmDeleteBtn').href = 'index.php?delete=' + id;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
  }
</script>

<?php
// Include footer
include_once '../../includes/footer.php';
?>
