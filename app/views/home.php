<head>
  <link rel="stylesheet" href="<?= CSS_URL ?>/home.css">
</head>
<body>
  <div class="home">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        
          <?php 
          foreach ($banners as  $banner) {
            echo '
            <div class="swiper-slide">
            <img src="'.htmlspecialchars(IMAGE_URL . '/' . ltrim($banner['img_url'], '/'), ENT_QUOTES, 'UTF-8').'" alt=""/>
            </div>
            ';
            }?>
          
        </div>
        <!-- Nút điều hướng -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- Nút tròn nhỏ -->
        <div class="swiper-pagination"></div>
    </div>

    <?php 
    if (!empty($showList)) {
      foreach ($showList as $List) { ?>
      <div class="new-product">
        <h1><?= htmlspecialchars($List['tieu_de']) ?></h1>
      
        <?php if (!empty($List['products'])) { ?>
          <div class="product-list">
            <?php foreach ($List['products'] as $product) { ?>
              <a class="product-box" href="<?= BASE_URL ?>/index.php?url=product_detail&product_id=<?= $product['id'] ?>">
                <span class="label-hot">🔥 Mới</span>
                <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($product['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="">
                <div class="product-name">
                  <h2><?= htmlspecialchars($product['ten']) ?></h2>
                </div>
              </a>
            <?php } ?>
          </div>
          <h3><a href="<?= BASE_URL ?>/index.php?url=product_list&category_id=<?= $List['danh_muc_id']?>">Xem thêm</a></h3>
          <?php } else { ?>
            <p style="font-size: 20px; padding: 40px 0px;">⚠️ Danh mục này hiện chưa có sản phẩm nào. Vui lòng kiểm tra trong phần quản trị.</p>
            <?php } ?>
          </div>
          <?php } } else { ?>
            <p style="font-size: 20px; padding: 40px 0px;">❗ Hiện không có danh sách hiển thị nào được cấu hình trong trang chủ. Vui lòng kiểm tra bảng <strong>Danh sách trang chủ</strong>.</p>
          <?php } ?>


    <img class="qc" src="<?= IMAGE_URL ?>/Capture2.PNG" alt="">
  </div>
  
  <script src="<?= JS_URL ?>/home.js"></script>
</body>

  