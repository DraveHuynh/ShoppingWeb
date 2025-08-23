<?php
  session_start();
  ob_start();
  require_once dirname(__DIR__, 1)."/config/init.php";
  include ROOT_PATH."/app/models/CategoryModel.php";
  include ROOT_PATH."/app/models/ProductModel.php";
  include ROOT_PATH."/app/models/ImagesProductModel.php";
  include ROOT_PATH."/app/models/CustomerModel.php";
  include ROOT_PATH."/app/models/BannerModel.php";
  include ROOT_PATH."/app/models/OrderModel.php";
  include ROOT_PATH.'/app/models/AddressModel.php';
  include ROOT_PATH.'/app/models/showHomeModel.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Web_battery</title>
    <link rel="stylesheet" href= "<?= CSS_URL ?>/admin.css" />
    <script
      type="module"
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"
    ></script>
    <script
      nomodule
      src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"
    ></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
  </head>

  <body>
    <?php
      include "menu-bar.php";
      $page = isset($_GET['page']) ? $_GET['page'] :'Products';
      include $page.'.php';
    ?>

    <script>
      // Xóa class active của tất cả các mục trước khi thêm class active cho mục đang chọn
      document.querySelectorAll('.menu-bar .menu').forEach(item => {
        item.classList.remove('active');
      });

      // Thêm class active cho mục đang chọn
      const page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'statistical'; ?>';
      const activeElement = document.querySelector(`.nav-${page}`);
      if (activeElement) {
        activeElement.parentElement.classList.add('active'); // Thêm class 'active' vào thẻ <li> cha
      }
    </script>

    <script src="<?= JS_URL ?>/admin.js"></script>
  </body>
</html>

