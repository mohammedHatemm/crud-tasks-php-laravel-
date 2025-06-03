<?php
// Include database and object files
require_once '../../config/database.php';
require_once '../../models/News.php';
require_once '../../models/Category.php';

// بدء الجلسة إذا لم تكن قد بدأت بالفعل
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login/login.php?error=" . urlencode("يجب تسجيل الدخول للوصول إلى هذه الصفحة"));
    exit();
}

$database = new Database();
$db = $database->getConnection();

$news = new News($db);

// تحديد ما إذا كان المستخدم مشرفًا أم مستخدمًا عاديًا
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
$user_id = $_SESSION['user_id'];

// معالجة حذف الخبر
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $news->id = $_GET['delete'];

    // التحقق من أن المستخدم العادي يمكنه فقط حذف أخباره
    if (!$is_admin) {
        // التحقق من أن الخبر ينتمي للمستخدم الحالي
        $stmt = $news->readOne();
        if ($news->user_id != $user_id) {
            $_SESSION['message'] = "ليس لديك صلاحية لحذف هذا الخبر.";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php");
            exit;
        }
    }

    if ($news->delete()) {
        $_SESSION['message'] = "تم حذف الخبر بنجاح.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "فشل في حذف الخبر.";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: index.php");
    exit;
}

// إذا كان المستخدم عاديًا، قم بتعيين معرف المستخدم للبحث عن أخباره فقط
if (!$is_admin) {
    $news->user_id = $user_id;
}

include_once '../../includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-6">
    <h2><?php echo $is_admin ? "إدارة الأخبار" : "أخباري"; ?></h2>
  </div>
  <div class="col-md-6 text-end">
    <a href="create.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> إضافة خبر جديد
    </a>
  </div>
</div>



<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="mb-0"><?php echo $is_admin ? "قائمة الأخبار" : "قائمة أخباري"; ?></h3>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>العنوان</th>
            <th>المحتوى</th>
            <th>تاريخ النشر</th>
            <th>آخر تحديث</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // قراءة الأخبار (جميع الأخبار للمشرف، أو أخبار المستخدم فقط للمستخدم العادي)
          $stmt = $is_admin ? $news->read() : $news->readByUser();

          if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['name']) . "</td>";
              echo "<td>" . substr(htmlspecialchars($row['content']), 0, 100) . "...</td>";
              echo "<td>" . date('Y-m-d', strtotime($row['created_at'])) . "</td>";
              echo "<td>" . date('Y-m-d', strtotime($row['updated_at'])) . "</td>";
              echo "<td>";
              echo "<a href='view.php?id={$row['id']}' class='btn btn-info btn-sm btn-action'><i class='fas fa-eye'></i></a>";
              echo "<a href='update.php?id={$row['id']}' class='btn btn-primary btn-sm btn-action'><i class='fas fa-edit'></i></a>";
              echo "<a href='#' onclick='confirmDelete({$row['id']})' class='btn btn-danger btn-sm btn-action'><i class='fas fa-trash'></i></a>";
              echo "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5' class='text-center'>لا توجد أخبار.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if ($is_admin): ?>
<div class="card mt-4">
  <div class="card-header bg-info text-white">
    <h3 class="mb-0">عرض الأخبار حسب الفئات</h3>
  </div>
  <div class="card-body">
    <div class="folder-container">
      <?php
      // Initialize category object
      $category = new Category($db);

      // Function to display categories as a folder structure with news
      function displayCategoryTreeWithNews($category, $news, $parent_id = null)
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
            echo '<div class="folder">' . htmlspecialchars($row['name']) . '</div>';
            echo '<ul class="folder-view hidden">';

            // Display news in this category
            $stmt_news = $news->readByCategory($row['id']);
            if ($stmt_news->rowCount() > 0) {
              while ($news_row = $stmt_news->fetch(PDO::FETCH_ASSOC)) {
                echo '<li>';
                echo '<a href="view.php?id=' . $news_row['id'] . '" class="file">' . htmlspecialchars($news_row['name']) . '</a>';
                echo '</li>';
              }
            } else {
              echo '<li><span class="file">لا توجد أخبار</span></li>';
            }

            echo '</ul>';

            // Display child categories if any
            if ($has_children) {
              displayCategoryTreeWithNews($category, $news, $row['id']);
            }

            echo '</li>';
          }

          echo '</ul>';
        }
      }

      // Display the category tree with news
      displayCategoryTreeWithNews($category, $news);
      ?>
    </div>
  </div>
</div>
<?php endif; ?>

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
