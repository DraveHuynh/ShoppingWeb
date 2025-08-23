<div class="page">
  <h1>Quản lý danh mục</h1>
  <div class="category-container">
    <table>
    <tr class="title">
    <th>ID</th>
    <th>Tên danh mục</th>
    <th>Hành động</th>
    </tr>
    <?php
      $categorys = getAllCategorys();
      if (!empty($categorys)) {
        foreach ($categorys as $category) {
          echo '
            <tr>
              <td>' . htmlspecialchars($category['id']) . '</td>
              <td>' . htmlspecialchars($category['ten']) . '</td>

              <td>
                  <form method="POST" action="">
                    <input type="hidden" name="Edit" value="' . htmlspecialchars($category['id']) . '">
                    <input class="addbtn" type="submit" value="Sửa">
                  </form>
              </td>
            </tr>
            ';
          }
        } else {echo '<tr><td colspan="9">Không có danh mục nào!</td></tr>';}
        ?>
    </table>

    <div class="add-category">
      <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/CategoryController.php">
        <label for="name">Tên danh mục:</label>
        <input type="name" name="name">
        <input type="submit" name="Add" value="Thêm danh mục">
      </form>
    </div>

<!-- Form sửa danh mục --> 
<?php 
if (isset($_POST['Edit']) && ($_POST['Edit'])) {
  $category_id = $_POST['Edit'];
  $category = getCategory($category_id);

?>

<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Chỉnh sửa thông tin danh mục</h2>
    <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/CategoryController.php">
      <input type="hidden" name="id" value="<?= htmlspecialchars($category_id) ?>">
          
      <label for="category_name">Tên sản phẩm:</label>
      <input type="text" name="category_name" value="<?= htmlspecialchars($category['ten']) ?>" required>


      <input type="submit" name="saveChanges" value="Lưu thay đổi">
    </form>
    
  </div>
</div>

<?php } ?>
</div>