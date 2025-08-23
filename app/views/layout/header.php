<?php  
require_once ROOT_PATH.'/app/models/CategoryModel.php';
$categories = getAllCategorys();
?>
<head>
  <link rel="stylesheet" href="<?= CSS_URL ?>/header.css">
</head>
<body>
  <header class="header">
    <nav class="navbar">
      <!-- Bên trái: menu + tìm kiếm -->
      <div class="nav-left">
        <div class="menu-toggle">
          <i class='bx bx-menu'></i>
        </div>
        <form action="<?= BASE_URL ?>/index.php?url=product_list" method="post" class="search-container">
        <button type="submit" class="search-btn">
            <ion-icon name="search-outline" style="font-size: 40px;"></ion-icon>
          </button>
          <input 
            type="text" 
            name="search" 
            id="search" 
            placeholder="Tìm kiếm" 
            class="search-input"
          >
          
        </form>
      </div>

      <!-- Ở giữa: logo -->
      <div class="nav-center">
        <a href="<?= BASE_URL ?>">
          <img src="<?= IMAGE_URL ?>/logo_new.png" alt="Logo"/>
        </a>
      </div>

      <!-- Bên phải: giỏ hàng + người dùng -->
      <div class="nav-right user-menu-wrapper">
        <?php if (isset($_SESSION['customer_id'])): ?>
          <a href="<?= BASE_URL ?>/index.php?url=cart" class="cart-icon">
            <i><ion-icon name="cart-outline"></ion-icon></i>
          </a>
        <?php endif; ?>

        <div class="user-icon" onclick="toggleDropdownIcon()">
          <ion-icon name="person-outline"></ion-icon>
        </div>

        <div class="dropdown-menu-icon" id="userDropdownIcon">
          <?php if (isset($_SESSION['customer_id'])): ?>
            <a href="<?= BASE_URL ?>/index.php?url=user"><ion-icon name="person"></ion-icon> Thông tin</a>
            <a href="<?= BASE_URL ?>/index.php?url=changepass"><ion-icon name="key"></ion-icon> Đổi mật khẩu</a>
            <a href="<?= BASE_URL ?>/index.php?url=address"><ion-icon name="map"></ion-icon> Địa chỉ</a>
            <a href="<?= BASE_URL ?>/index.php?url=order"><ion-icon name="bag-check"></ion-icon> Đơn hàng</a>
            <a href="<?= BASE_URL ?>/index.php?url=logout"><ion-icon name="log-out-outline"></ion-icon> Đăng xuất</a>
          <?php else: ?>
            <a href="<?= BASE_URL ?>/index.php?url=login">Đăng nhập</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>

    <!-- Sidebar menu -->
    <aside class="sidebar">
      <ul id="mainMenu">
      <li class="mobile-search">
        <form action="search.php" method="post" class="mobile-search-container" >
        
        <input 
            type="text" 
            name="search" 
            id="search" 
            placeholder="Tìm kiếm" 
            class="search-input"
          >
          <button type="submit" class="search-btn">
          <ion-icon name="search-outline" ></ion-icon>
          </button>
        </form>
      </li>

        <li class="next-btn" data-submenu="products">
          Sản phẩm
          <ion-icon name="arrow-forward-circle-outline" style="font-size: 30px;"></ion-icon>
        </li>
      </ul>

      <ul id="subMenu-products" class="submenu">
        <li class="back-btn">
          Quay lại 
          <ion-icon name="arrow-back-circle-outline" style="font-size: 30px;"></ion-icon>
        </li>
        <li><a href="<?= BASE_URL ?>/index.php?url=product_list">Tất cả sản phẩm</a></li>
        <?php foreach($categories as $category): ?>
          <li>
            <a href="<?= BASE_URL ?>/index.php?url=product_list&category_id=<?= $category['id'] ?>">
              <?= htmlspecialchars($category['ten']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </aside>
  </header>

  <script src="<?= JS_URL ?>/header.js"></script>
</body>
