<?php
// Include database and object files
require_once '../../config/database.php';
require_once '../../models/Category.php';
require_once '../../models/News.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$category = new Category($db);
$news = new News($db);

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID passed
if ($id <= 0) {
  header("Location: index.php");
  exit;
}

// Set category ID and read details
$category->id = $id;
$category_exists = $category->readOne();

// If category doesn't exist
if (!$category_exists) {
  $_SESSION['message'] = "الفئة غير موجودة.";
  $_SESSION['message_type'] = "danger";
  header("Location: index.php");
  exit;
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
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($category->name); ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">تفاصيل الفئة</h3>
        <div>
          <a href="update.php?id=<?php echo $category->id; ?>" class="btn btn-light btn-sm">
            <i class="fas fa-edit"></i> تعديل
          </a>
          <a href="#" onclick="confirmDelete(<?php echo $category->id; ?>)" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i> حذف
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th style="width: 150px;">الاسم</th>
            <td><?php echo htmlspecialchars($category->name ?? ''); ?></td>
          </tr>
          <tr>
            <th>الوصف</th>
            <td><?php echo htmlspecialchars($category->description ?? ''); ?></td>
          </tr>
          <tr>
            <th>الفئة الأم</th>
            <td>
              <?php
              if ($category->parent_id) {
                $parent = new Category($db);
                $parent->id = $category->parent_id;
                if ($parent->readOne()) {
                  echo '<a href="view.php?id=' . $parent->id . '">'
                    . htmlspecialchars($parent->name ?? '') . '</a>';
                } else {
                  echo 'لا يوجد';
                }
              } else {
                echo 'لا يوجد (فئة رئيسية)';
              }
              ?>
            </td>
          </tr>
          <tr>
            <th>تاريخ الإنشاء</th>
            <td><?php echo $category->created_at ? date('Y-m-d H:i', strtotime($category->created_at)) : ''; ?></td>
          </tr>
          <tr>
            <th>آخر تحديث</th>
            <td><?php echo $category->updated_at ? date('Y-m-d H:i', strtotime($category->updated_at)) : ''; ?></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="card mb-4">
      <div class="card-header bg-info text-white">
        <h3 class="mb-0">الأخبار في هذه الفئة</h3>
      </div>
      <div class="card-body">
        <?php
        // Read news by category
        $stmt = $news->readByCategory($id);

        if ($stmt->rowCount() > 0) {
          echo '<div class="table-responsive">';
          echo '<table class="table table-striped table-hover">';
          echo '<thead>';
          echo '<tr>';
          echo '<th>العنوان</th>';
          echo '<th>تاريخ النشر</th>';
          echo '<th>الإجراءات</th>';
          echo '</tr>';
          echo '</thead>';
          echo '<tbody>';

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . date('Y-m-d', strtotime($row['created_at'])) . '</td>';
            echo '<td>';
            echo '<a href="../../news.php?id=' . $row['id'] . '" class="btn btn-info btn-sm btn-action" target="_blank"><i class="fas fa-eye"></i></a>';
            echo '<a href="../news/update.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm btn-action"><i class="fas fa-edit"></i></a>';
            echo '</td>';
            echo '</tr>';
          }

          echo '</tbody>';
          echo '</table>';
          echo '</div>';
        } else {
          echo '<div class="alert alert-info">لا توجد أخبار في هذه الفئة حالياً.</div>';
        }
        ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card mb-4">
      <div class="card-header bg-success text-white">
        <h3 class="mb-0">الفئات الفرعية</h3>
      </div>
      <div class="card-body">
        <?php
        // Get child categories
        $category->id = $id;
        $stmt = $category->readChildren();

        if ($stmt->rowCount() > 0) {
          echo '<ul class="list-group">';

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<a href="view.php?id=' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</a>';
            echo '<div>';
            echo '<a href="update.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm btn-action"><i class="fas fa-edit"></i></a>';
            echo '<a href="#" onclick="confirmDelete(' . $row['id'] . ')" class="btn btn-danger btn-sm btn-action"><i class="fas fa-trash"></i></a>';
            echo '</div>';
            echo '</li>';
          }

          echo '</ul>';
        } else {
          echo '<div class="alert alert-info">لا توجد فئات فرعية.</div>';
        }
        ?>

        <div class="mt-3">
          <a href="create.php" class="btn btn-success w-100">
            <i class="fas fa-plus"></i> إضافة فئة فرعية جديدة
          </a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-warning text-dark">
        <h3 class="mb-0">روابط سريعة</h3>
      </div>
      <div class="card-body">
        <div class="list-group">
          <a href="../../category.php?id=<?php echo $category->id; ?>" class="list-group-item list-group-item-action" target="_blank">
            <i class="fas fa-globe"></i> عرض في الموقع
          </a>
          <a href="update.php?id=<?php echo $category->id; ?>" class="list-group-item list-group-item-action">
            <i class="fas fa-edit"></i> تعديل الفئة
          </a>
          <a href="#" onclick="confirmDelete(<?php echo $category->id; ?>)" class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-trash"></i> حذف الفئة
          </a>
          <a href="index.php" class="list-group-item list-group-item-action">
            <i class="fas fa-list"></i> قائمة الفئات
          </a>
        </div>
      </div>
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
