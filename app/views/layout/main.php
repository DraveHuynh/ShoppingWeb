<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Website' ?></title>
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    /> 
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="<?= CSS_URL ?>/style.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>
<body>
  <div class="wrapper">
    <header><?php require_once ROOT_PATH.'/app/views/layout/header.php'?></header>
    <main>
      <?php require_once $view ?>
      <?php require_once ROOT_PATH.'/app/views/layout/floating-buttons.php'?>
    </main>
    <footer><?php require_once ROOT_PATH.'/app/views/layout/footer.php'?></footer>
  </div>
    
    
    
    
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>  
</body>
</html>
