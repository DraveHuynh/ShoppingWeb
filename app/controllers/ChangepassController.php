<?php
require_once ROOT_PATH.'/app/models/CustomerModel.php';


class ChangepassController {
    public function index() {
        $title = "Đổi mật khẩu";

        $view = ROOT_PATH.'/app/views/changepass.php';

        $customer = getCustomer($_SESSION['customer_id']);

        $errors = [];

        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }

    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $oldpass = $_POST['oldpass'] ?? '';
            $newpass = $_POST['newpass'] ?? '';
            $comfirmpass = $_POST['comfirmpass'] ?? '';
    
            $errors = [];
    
            // Giả sử lấy thông tin user từ session (hoặc lấy từ DB)
            $customer_id = $_SESSION['customer_id'];
            $user = getCustomer($customer_id); // giả sử trả về ['password' => '...']
    
            // 1. Kiểm tra oldpass
            if (empty($oldpass)) {
                $errors['oldpass'] = 'Vui lòng nhập mật khẩu cũ.';
            } elseif (!password_verify($oldpass, $user['password'])) {
                $errors['oldpass'] = 'Mật khẩu cũ không chính xác.';
            } else {
                // Nếu oldpass đúng, mới tiếp tục kiểm tra so với newpass
                if (!empty($newpass) && password_verify($newpass, $user['password'])) {
                    $errors['newpass'] = 'Mật khẩu mới không được trùng với mật khẩu cũ.';
                }
            }
    
            // 2. Kiểm tra newpass
            if (empty($newpass)) {
                $errors['newpass'] = 'Mật khẩu mới không được để trống.';
            } elseif (strlen($newpass) < 8) {
                $errors['newpass'] = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
            } elseif (!preg_match('/[A-Z]/', $newpass) ||
                      !preg_match('/[a-z]/', $newpass) ||
                      !preg_match('/[0-9]/', $newpass) ||
                      !preg_match('/[\W]/', $newpass)) {
                $errors['newpass'] = 'Mật khẩu mới phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.';
            }
    
            // 3. Kiểm tra comfirmpass
            if (empty($comfirmpass)) {
                $errors['comfirmpass'] = 'Vui lòng xác nhận lại mật khẩu.';
            } elseif ($newpass !== $comfirmpass) {
                $errors['comfirmpass'] = 'Mật khẩu xác nhận không khớp.';
            }
    
            // Nếu không có lỗi, cập nhật mật khẩu
            if (empty($errors)) {
                $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
                updatePassword($customer_id, $hashedPassword);
                $_SESSION['success'] = 'Đổi mật khẩu thành công.';
            } else {
                $_SESSION['errors'] = $errors;
            }
        }
    
        header("Location: " . BASE_URL . "/index.php?url=changepass");
        exit;
    }
    
}
