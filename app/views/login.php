<?php
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  $activeForm = $_SESSION['active_form'] ?? 'login'; // để xác định hiển thị form login hay register
?>

<body>
<link rel="stylesheet" href="<?= CSS_URL ?>/login.css">
  <div class="login">
    <div class="content">
      <div class="container <?= isset($_SESSION['active_form']) && $_SESSION['active_form'] === 'register' ? 'active' : '' ?>">
        <div class="form-box login">
          <form method="post" action="<?= BASE_URL ?>/index.php?url=login/login">
            <h1>Đăng nhập</h1>

            <div class="input-box">
              <input type="text" name="l_email" placeholder="Email" value="<?= $old['l_email'] ?? '' ?>">
              <i class="bx bxs-envelope"></i>
              <?php if (!empty($errors['l_email'])): ?>
                <small class="error show"><?= $errors['l_email'] ?></small>
              <?php endif; ?>
            </div>
            
            <div class="input-box">
              <input type="password" name="l_password" placeholder="Password" class="password-input">
              <i class="bx bxs-lock-alt toggle-password toggle-password-icon" style="cursor: pointer;"></i>
              <?php if (!empty($errors['l_password'])): ?>
                <small class="error show"><?= $errors['l_password'] ?></small>
              <?php endif; ?>
            </div>
          
            <div class="forgot-link">
              <a href="#">Quên mật khẩu?</a>
            </div>
        
            <input type="submit" value="Đăng nhập" class="btn">
            <!-- <p>hoặc đăng nhập với</p>
            <div class="social-icons">
              <a href="#"><i class="bx bxl-google"></i></a>
              <a href="#"><i class="bx bxl-facebook"></i></a>
            </div> -->
          </form>
        </div>
    
        <div class="form-box register">
          <form method="post" action="<?= BASE_URL ?>/index.php?url=login/register">
            <h1>Đăng ký</h1>
        
            <div class="input-box">
              <input type="text" name="username" placeholder="Họ và tên" value="<?= $old['username'] ?? '' ?>">
              <i class="bx bxs-user"></i>
              <?php if (!empty($errors['username'])): ?>
                <small class="error show"><?= $errors['username'] ?></small>
              <?php endif; ?>
            </div>
        
            <div class="input-box">
              <input type="text" name="email" placeholder="Email" value="<?= $old['email'] ?? '' ?>">
              <i class="bx bxs-envelope"></i>
              <?php if (!empty($errors['email'])): ?>
                <small class="error show"><?= $errors['email'] ?></small>
              <?php endif; ?>
            </div>
        
            <div class="input-box">
              <input type="text" name="phoneN" placeholder="Phone number" value="<?= $old['phoneN'] ?? '' ?>">
              <i class="bx bxs-phone"></i>
              <?php if (!empty($errors['phoneN'])): ?>
                <small class="error show"><?= $errors['phoneN'] ?></small>
              <?php endif; ?>
            </div>
        
            <div class="input-box">
              <input type="password" name="password" placeholder="Password" class="password-input">
              <i class="bx bxs-lock-alt toggle-password toggle-password-icon" style="cursor: pointer;"></i>
              <?php if (!empty($errors['password'])): ?>
                <small class="error show"><?= $errors['password'] ?></small>
              <?php endif; ?>
            </div>
        
            <input type="submit" class="btn" value="Đăng ký">
            <!-- <p>or register with social platforms</p>
            <div class="social-icons">
              <a href="#"><i class="bx bxl-google"></i></a>
              <a href="#"><i class="bx bxl-facebook"></i></a>
            </div> -->
          </form>
        </div>
    
        <div class="toggle-box">
          <div class="toggle-panel toggle-left">
            <h1>Rất vui gặp bạn!</h1>
            <p>Bạn là người mới?</p>
            <button class="btn register-btn">Đăng ký ngay</button>
          </div>
      
          <div class="toggle-panel toggle-right">
            <h1>Chào mừng trở lại!</h1>
            <p>Bạn đã có tài khoản rồi à?</p>
            <button class="btn login-btn">Đăng nhập thôi</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['active_form']); ?>

  <script src="<?= JS_URL ?>/login.js"></script>
</body>

