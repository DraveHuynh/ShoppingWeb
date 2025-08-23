//Xử lý chuyển trang
const container = document.querySelector(".container");
const registerBtn = document.querySelector(".register-btn");
const loginBtn = document.querySelector(".login-btn");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

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
