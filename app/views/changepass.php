<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/changepass.css">
  <div class="user">
    <div class="content">
      <div class="container">
        <div class="form-box">
          <form method="post" action="<?= BASE_URL ?>/index.php?url=changepass/update">
            <h1>Thay đổi mật khẩu</h1>
            <div class="input-box">
              <input type="password" name="oldpass" class="password-input" placeholder="Mật khẩu cũ"/>
              <i class="bx bxs-lock-alt toggle-password toggle-password-icon" style="cursor: pointer;"></i>
              <div class="error" id="oldpass-error"><?= $errors['oldpass'] ?? '' ?></div>
            </div>

            <div class="input-box">
              <input type="password" name="newpass" class="password-input" placeholder="Mật khẩu mới" />
              <i class="bx bxs-lock-alt toggle-password toggle-password-icon" style="cursor: pointer;"></i>
              <div class="error" id="newpass-error"><?= $errors['newpass'] ?? '' ?></div>
            </div>

            <div class="input-box">
              <input type="password" name="comfirmpass" class="password-input" placeholder="Xác nhận mật khẩu" />
              <i class="bx bxs-lock-alt toggle-password toggle-password-icon" style="cursor: pointer;"></i>
              <div class="error" id="comfirmpass-error"><?= $errors['comfirmpass'] ?? '' ?></div>
            </div>

            <input type="submit" name="update" value="Cập nhật" class="btn" />
            
          </form> 
        </div>
      </div>
    </div>
  </div>


  <script>
    //Xử lý ẩn hiện mật khẩu
    // Lấy tất cả các trường nhập mật khẩu và các icon ẩn hiện
    const passwordInputs = document.querySelectorAll(".password-input");
    const toggleIcons = document.querySelectorAll(".toggle-password-icon");

    // Lặp qua từng trường để gán sự kiện
    passwordInputs.forEach((passwordInput, index) => {
      const toggleIcon = toggleIcons[index]; // Lấy icon tương ứng

      toggleIcon.addEventListener("click", () => {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";

        // Đổi icon tương ứng
        toggleIcon.classList.toggle("bxs-lock-alt", !isPassword); // đóng
        toggleIcon.classList.toggle("bxs-lock-open-alt", isPassword); // mở
      });
    });
  </script>
</body>


