<div class="page">
    <h1>Quản lý Banner</h1>
    <div class="category-container">
        <div class="banner-item">
            <?php 
            $banners = getAllBanners();
            foreach ($banners as $banner) { ?>
                <div style="margin-bottom: 20px;">
                    <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($banner['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>" 
                        alt="Banner" style="width: 500px; height: auto; display: block;">

                    <!-- Form xóa banner -->
                    <form method="POST" action="<?= BASE_URL ?>/app/controllers/BannerController.php" style="margin-top: 10px;">
                        <input type="hidden" name="banner_id" value="<?= $banner['id'] ?>">
                        <input type="submit" name="Delete" value="Xóa ảnh" class="delete-button">
                    </form>
                </div>
            <?php } ?>
        </div>

        <div class="add-category">
            <form id="editForm" method="POST" action="<?= BASE_URL ?>/app/controllers/BannerController.php" enctype="multipart/form-data">
                <label for="url">Chọn ảnh:</label>
                <input type="file" name="url" accept="image/*" required>
                <input type="submit" name="Add" value="Thêm ảnh">
            </form>
        </div>
    </div>
</div>
