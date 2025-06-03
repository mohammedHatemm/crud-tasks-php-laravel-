<?php
// Include database and object files
require_once '../../config/database.php';
require_once '../../models/News.php';
require_once '../../models/Category.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
$news = new News($db);
$category = new Category($db);

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If no ID passed
if ($id <= 0) {
  header("Location: index.php");
  exit;
}

// Set news ID and read details
$news->id = $id;
$news_exists = $news->readOne();

// If news doesn't exist
if (!$news_exists) {
  $_SESSION['message'] = "الخبر غير موجود.";
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
        <li class="breadcrumb-item"><a href="index.php">إدارة الأخبار</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($news->name); ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0">تفاصيل الخبر</h3>
        <div>
          <a href="update.php?id=<?php echo $news->id; ?>" class="btn btn-light btn-sm">
            <i class="fas fa-edit"></i> تعديل
          </a>
          <a href="#" onclick="confirmDelete(<?php echo $news->id; ?>)" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i> حذف
          </a>
        </div>
      </div>
      <div class="card-body">
        <h4 class="mb-3"><?php echo htmlspecialchars($news->name); ?></h4>

        <div class="mb-3">
          <small class="text-muted">تاريخ النشر: <?php echo date('Y-m-d H:i', strtotime($news->created_at)); ?></small>
          <br>
          <small class="text-muted">آخر تحديث: <?php echo date('Y-m-d H:i', strtotime($news->updated_at)); ?></small>
        </div>

        <div class="news-content mb-4">
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
            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
            echo '<a href="../categories/view.php?id=' . $category->id . '">' . htmlspecialchars($category->name) . '</a>';
            echo '<a href="../categories/view.php?id=' . $category->id . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>';
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
      <div class="card-header bg-warning text-dark">
        <h3 class="mb-0">روابط سريعة</h3>
      </div>
      <div class="card-body">
        <div class="list-group">
          <a href="../../news.php?id=<?php echo $news->id; ?>" class="list-group-item list-group-item-action" target="_blank">
            <i class="fas fa-globe"></i> عرض في الموقع
          </a>
          <a href="update.php?id=<?php echo $news->id; ?>" class="list-group-item list-group-item-action">
            <i class="fas fa-edit"></i> تعديل الخبر
          </a>
          <a href="#" onclick="confirmDelete(<?php echo $news->id; ?>)" class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-trash"></i> حذف الخبر
          </a>
          <a href="index.php" class="list-group-item list-group-item-action">
            <i class="fas fa-list"></i> قائمة الأخبار
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
        هل أنت متأكد من حذف هذا الخبر؟
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
