<div class="page">

  <h1>Quản lý sản phẩm</h1>

  <form class="add" method="POST" action="">
    <input class="addbtn" type="submit" name="Add" value="Thêm sản phẩm">
  </form>

  <div class="product-container">
    
            <?php 
            $products = getAllProducts();
            foreach ($products as $product) {
                echo '
                <form action="" method="POST" id="productForm">
                <div class="product-box">
                <div class="product-item" onclick="submitForm('.htmlspecialchars($product['id']).')">
                    <img src="' . htmlspecialchars(IMAGE_URL . '/' . ltrim($product['img_url'], '/'), ENT_QUOTES, 'UTF-8') . '" alt="" width="100px">
                    <div class="product-name">'.htmlspecialchars($product['ten']).'</div>
                </div>
                </div>
                <input type="hidden" name="product_id" id="product_id">
            <input type="hidden" name="show_product" value="1">  
    </form>
                ';
            }
            ?>
  </div>

<!-- Form nhập dữ liệu -->
<?php
if (isset($_POST['Add']) && ($_POST['Add'])) {
  $categorys = getAllCategorys();
?>
<div id="editModal" class="modal" style="display:block;">
  <div class="modal-content" style="width: 1000px; max-height: 100vh; overflow: auto;">
    <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
    <h2>Thêm sản phẩm</h2>
    <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/ProductController.php" enctype="multipart/form-data">  
      <div class="scroll-container">
        <h3>Tên sản phẩm:</h3>
        <input type="text" name="products_name" required placeholder="Nhập tên sản phẩm">
        
        <h3>Danh mục:</h3>
        <select name="category_id" class="category_id" required onchange="handleSelectChange(this)">
          <option value="">-- Chọn danh mục --</option>
          <?php foreach ($categorys as $category) { ?>
            <option value="<?= htmlspecialchars($category['id']) ?>">
              <?= htmlspecialchars($category['ten']) ?>
            </option>
            <?php } ?>
          </select>
          
        <h3>Hình ảnh sản phẩm:</h3>
        <div class="image-container">
          <!-- Ảnh bìa -->
          <label for="product_images_cover" class="image-upload-label">
            <div class="upload-box" id="upload-box-cover"><span>+</span></div>
            <p class="image-caption">* Ảnh bìa</p>
          </label>
          <input type="file" id="product_images_cover" name="cover_photo" style="display: none;" required onchange="previewImage(event, 'cover')">

          <!-- 8 Ô chọn ảnh còn lại -->
          <?php for ($i = 1; $i <= 8; $i++) { ?>
            <label for="product_images_<?= $i ?>" class="image-upload-label">
              <div class="upload-box" id="upload-box-<?= $i ?>">
                <span>+</span>
              </div>
              <p class="image-caption">Ảnh <?= $i ?></p>
            </label>
            <input type="file" id="product_images_<?= $i ?>" name="product_images[]" style="display: none;" onchange="previewImage(event, <?= $i ?>)">
          <?php } ?>
        </div>

        <h3>Mô tả sản phẩm:</h3>
        <textarea id="product_description" name="product_description" rows="10" cols="50" placeholder="Nhập mô tả sản phẩm"></textarea>

        <h3>Giá bán:</h3>
        <input type="number" name="price" placeholder="Giá bán">

      </div>
      <input type="submit" name="Add" value="Thêm sản phẩm">
    </form>
  </div>
</div>
<?php } ?>

<!-- Form xem sản phẩm -->   
<?php
if (isset($_POST['show_product']) && ($_POST['show_product'])) {
  $product_id = $_POST['product_id'];
  $product = getProduct($product_id);
  $product_images = getImages($product_id);
  $category = getCategory($product['danh_muc_id']);
?>
<div id="editModal" class="modal" style="display:block;">
  <div class="modal-content" style="width: 1000px; max-height: 100vh; overflow: auto;">
    <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
    <h2>Sản phẩm</h2>
    
    <div class="scroll-container">
    <div class="image-container">
    <label class="image-upload-label">
      <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($product['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="" style="width: 120px; height: 120px;">
      <p class="image-caption">Ảnh bìa</p>
      </label>

      <?php 
      foreach ($product_images as $key => $product_image) { 
        $i = $key + 1;
        ?>
        <label class="image-upload-label">
        <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($product_image['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>" 
        alt="" style="width: 120px; height: 120px;">
        <p class="image-caption">Ảnh <?= $i ?></p>
        </label>
      <?php } ?>
    </div>
    
    <table>
    <tr class="title">
      <th>ID</th>
      <th>Tên sản phảm</th>
      <th>Danh mục</th>
      <th>Giá</th>
      <th>Ngày tạo</th>
      <th>Ngày cập nhật</th>
      <th>Trạng thái</th>
    </tr>
    <tr>
      <td><?= $product['id'] ?></td>
      <td><?= $product['ten'] ?></td>
      <td><?= $category['ten'] ?></td>
      <td><?= $product['gia'] ?></td>
      <td><?= $product['ngay_tao'] ?></td>
      <td><?= $product['ngay_cap_nhat'] ?></td>
      <td>
        <?php 
        if ($product['trang_thai'] == 1) {
            echo "Mở bán";
        } else {
            echo "Ngừng bán";
        }
        ?>
      </td>
    </tr>
    </table>

    <textarea readonly style="margin-top: 20px;"><?= htmlspecialchars($product['mo_ta']) ?></textarea>
    <form method="POST" action="">
      <input type="hidden" name="Edit" value="<?= htmlspecialchars($product_id) ?>">
      <input type="submit" value="Sửa thông tin">
    </form>
    </div>
    
  </div>
</div>
<?php } ?>

<!-- Form sửa sản phẩm --> 
<?php 
if (isset($_POST['Edit']) && ($_POST['Edit'])) {
  $product_id = $_POST['Edit'];
  $product = getProduct($product_id);
  $categorys = getAllCategorys();
?>

<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Chỉnh sửa thông tin sản phẩm</h2>
    <div class="scroll-container">
    <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/ProductController.php">
      <input type="hidden" name="id" value="<?= htmlspecialchars($product_id) ?>">
          
      <label for="products_name">Tên sản phẩm:</label>
      <input type="text" name="products_name" value="<?= htmlspecialchars($product['ten']) ?>" required>

      <label for="category_id">Danh mục:</label>
        <select name="category_id" class="category_id">
          <?php foreach ($categorys as $category) { ?>
            <option value="<?= htmlspecialchars($category['id']) ?>" <?= $category['id'] == $product['danh_muc_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($category['ten']) ?>
            </option>
            <?php } ?>
          </select>

      <label for="price">Giá:</label>
      <input type="text" name="price" value="<?= htmlspecialchars($product['gia']) ?>" required>

      <label for="product_description">Mô tả:</label>
      <textarea id="product_description" name="product_description" rows="10" cols="50" placeholder="Nhập mô tả sản phẩm"><?= htmlspecialchars($product['mo_ta']) ?></textarea>

      <label for="status">Trạng thái:</label>
      <select name="status">
        <option value="1" <?= $product['trang_thai'] == 1 ? 'selected' : '' ?>>Mở bán</option>
        <option value="0" <?= $product['trang_thai'] == 0 ? 'selected' : '' ?>>Ngưng bán</option>
      </select>

      <input type="submit" name="saveChanges" value="Lưu thay đổi">
    </form>
    </div>
    
  </div>
</div>

<?php } ?>

<script>
  function previewImage(event, index) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const uploadBox = document.getElementById('upload-box-' + index);
        if (uploadBox) {
          uploadBox.style.backgroundImage = `url(${e.target.result})`;
          uploadBox.style.backgroundSize = 'cover';
          uploadBox.style.backgroundPosition = 'center';
          uploadBox.innerHTML = ''; // Xóa dấu "+"
        }
      }
      reader.readAsDataURL(file);
    }
  }

  function submitForm(productId) {
      document.getElementById("product_id").value = productId;
      document.getElementById("productForm").submit();
  }
</script>

<style>
  .scroll-container {
    max-height: 450px;
    overflow-y: auto;
    margin-top: 5px;
  }

  .close {
    float: right;
    cursor: pointer;
  }

  .image-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin: 15px 0 15px 0;
  }

  .upload-box {
    width: 120px;
    height: 120px;
    border: 2px dashed #d4af37;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    font-size: 40px;
    color: #ccc;
  }

  .image-caption {
    text-align: center;
    color: rgba(78, 78, 78, 0.6);
    font-size: 15px;
    margin-top: 5px;
    font-weight: 400;
  }

  textarea {
    width: 100%;
    height: 150px;
    min-height: 50px;
    resize: vertical;
    border-radius: 8px;
    padding: 10px;
    font-size: 18px;
  }

  #add-select {
    border: 2px dashed #d4af37;
    background-color: #f8f9fa;
    color: #d4af37;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  #add-select span {
    font-size: 20px;
  }

  .label-container {
    display: flex;
    align-items: center;
    width: 100%;
  }

  .delete-btn {
    margin-left: 10px;
    color: black;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    padding: 5px 10px;
  }

  
</style>
</div>