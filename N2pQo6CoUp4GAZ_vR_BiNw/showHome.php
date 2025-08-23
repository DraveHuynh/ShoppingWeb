<div class="page">

  <h1>Quản lý danh sách hiển thị trang chủ</h1>

  <form class="add" method="POST" action="">
    <input class="addbtn" type="submit" name="Add" value="Thêm danh sách hiển thị">
  </form>

  <div class="showHome-container">
    <table>
      <tr class="title">
        <th>Số thứ tự</th>
        <th>Danh mục</th>
        <th>Tiêu đề</th>
        <th>Hành động</th>
      </tr>

      <?php
      $lists = getAllshowHome();
      if (!empty($lists)) {
        foreach ($lists as $list) {
          $category = getCategory($list['danh_muc_id']);
      ?>
      <tr>
        <td><?= htmlspecialchars($list['stt']) ?></td>
        <td><?= htmlspecialchars($list['tieu_de']) ?></td>
        <td><?= htmlspecialchars($category['ten']) ?></td>
        <td>
          <div class="dropdown">
            <button class="dropbtn">Hành động</button>
            <div class="dropdown-content">
              <form method="POST" action="">
                <input type="hidden" name="list_id" value="<?= $list["id"] ?>">
                <input type="submit" name="Edit" value="Sửa">
              </form>
              <form method="POST" action="<?= BASE_URL ?>/app/controllers/ShowHomeController.php">
                <input type="hidden" name="list_id" value="<?= $list["id"] ?>">
                <input type="submit" name="Delete" value="Xóa">
              </form>
            </div>
          </div>
        </td>
      </tr>
      <?php } } else {echo '<tr><td colspan="9">Không có danh sách!</td></tr>';} ?>
    </table>
  </div>

<!-- Form nhập dữ liệu -->
<?php
if ((isset($_POST['Add']) && $_POST['Add']) || (isset($_POST['Edit']) && $_POST['Edit'])) {
  $categorys = getAllCategorys();
  $choose = isset($_POST['Add']) ? 'Add' : 'Edit';
  $title = $choose == "Add" ? "Thêm danh sách hiển thị" : "Sửa danh sách hiển thị";
  $list = $choose == "Edit" ? (getShowHome($_POST['list_id'])) : [];
  $danh_muc_id = $choose == "Edit" ? $list['danh_muc_id'] : 0;
  $tieu_de = $choose == "Edit" ? $list['tieu_de'] : "";

?>
<div id="editModal" class="modal" style="display:block;">
  <div class="modal-content" style="width: 650px; max-height: 100vh; overflow: auto;">
    <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
    <h2><?= $title ?></h2>
    <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/ShowHomeController.php" enctype="multipart/form-data">
      <h3>Tiêu đề:</h3>
      <input type="text" name="title" required placeholder="Ghi nội dung tiêu đề muốn hiển thị" value="<?= $tieu_de ?? "" ?>">
      
      <h3>Danh mục:</h3>
      <select name="category_id" class="category_id" required onchange="handleSelectChange(this)">
        <option value="">-- Chọn danh mục --</option>
        <?php foreach ($categorys as $category) { ?>
          <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category['id'] == $danh_muc_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($category['ten']) ?>
          </option>
        <?php } ?>
      </select>
      <input type="hidden" name="list_id" value="<?= $list['id'] ?? 0 ?>">
      <input type="submit" name="<?= $choose ?>" value="<?= $title ?>">
    </form>
  </div>
</div>
<?php } ?>
</div>