<head>
<link rel="stylesheet" href="<?= CSS_URL ?>/product_list.css">
</head>
<body>
  <div class="products">
      <h1><?= $title_product_list ?></h1>
      <div class="content">
        <?php foreach ($products as $product) {
          $class_img = isset($product['img_url']) ? 'product-img' : 'no-image';?>
          <a href="<?= BASE_URL.'/index.php?url=product_detail&product_id='.$product['id'] ?>" class="product">
            <div class="<?= htmlspecialchars($class_img) ?>">
              <?php 
              if ($class_img == 'product-img'){
                echo '
                <img src="'.htmlspecialchars(IMAGE_URL . '/' . ltrim($product['img_url'], '/'), ENT_QUOTES, 'UTF-8').'" alt="">
                ';
              }else {
                echo 'Không có ảnh';
              }
              ?>
            </div>
            <h3><?= htmlspecialchars($product['ten']) ?></h3>
          </a>
        <?php } ?>
    </div>
  </div>
</body>
