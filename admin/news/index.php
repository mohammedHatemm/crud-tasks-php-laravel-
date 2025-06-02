<?php
// Include database and object files
require_once '../../config/database.php';
require_once '../../models/News.php';
require_once '../../models/Category.php';


$database = new Database();
$db = $database->getConnection();


$news = new News($db);




include_once '../../includes/header.php';
?>

<div class="row mb-4">
  <div class="col-md-6">
    <h2>إدارة الأخبار</h2>
  </div>
  <div class="col-md-6 text-end">
    <a href="create.php" class="btn btn-primary">
      <i class="fas fa-plus"></i> إضافة خبر جديد
    </a>
  </div>
</div>



<div class="card">
  <div class="card-header bg-primary text-white">
    <h3 class="mb-0">قائمة الأخبار</h3>
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
          // Read all news
          $stmt = $news->read();

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
