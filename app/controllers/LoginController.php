<?php
require_once ROOT_PATH . '/app/models/CustomerModel.php';
require_once ROOT_PATH . '/app/models/CartModel.php';
require_once ROOT_PATH . '/app/models/ProductModel.php';

class LoginController {
    public function index() {
        $title = "Đăng nhập";
        $view = ROOT_PATH . '/app/views/login.php';
        
        require_once ROOT_PATH . '/app/views/layout/main.php';
    }

    public function login() {
        $email = $_POST['l_email'] ?? '';
        $password = $_POST['l_password'] ?? '';

        $errors = [];

        // Kiểm tra dữ liệu
        if (empty($email)) {
            $errors['l_email'] = 'Vui lòng nhập email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['l_email'] = 'Email không đúng định dạng';
        } elseif (!isEmailExists($email)) {
            $errors['l_email'] = 'Tài khoản không tồn tại';
        }
        
        $userId = isLoginValid($email, $password);
        if (empty($errors['l_email'])) { // Chỉ kiểm tra mật khẩu nếu email đã OK
            if (empty($password)) {
                $errors['l_password'] = 'Vui lòng nhập mật khẩu';
            } elseif (!$userId) {
                $errors['l_password'] = 'Sai mật khẩu';
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $_SESSION['active_form'] = 'login';
            header('Location: ?url=login/index');
            exit;
        }

        // Nếu đúng, chuyển tới trang khác (vd: dashboard)
        session_regenerate_id(true);
        $_SESSION['customer_id'] = $userId;
        $cart_items = getCart($userId); // Lấy danh sách sản phẩm từ DB
        $_SESSION['cart'] = []; // Khởi tạo mảng giỏ hàng
        foreach ($cart_items as $item) {
            if($item['trang_thai'] == 'in_cart')
            {$_SESSION['cart'][$item['san_pham_id']] = [
                'product_id' => $item['san_pham_id'],
                'quantity' => $item['so_luong'],
                'status' => true,
            ];}
        }

        header('Location: ?url=home');
        exit;
    }

    public function register() {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phoneN = trim($_POST['phoneN'] ?? '');
        $password = $_POST['password'] ?? '';
    
        $errors = [];
    
        // Kiểm tra họ tên (không chứa số, ký tự lạ, dài 2–50 ký tự)
        if (empty($username)) {
            $errors['username'] = 'Họ và tên không được để trống.';
        } elseif (!preg_match("/^[a-zA-ZÀ-Ỵà-ỵ\s'\-]{1,50}$/u", $username)) {
            $errors['username'] = 'Họ và tên không được chứa số hoặc ký tự đặc biệt';
        }
    
        // Kiểm tra email
        if (empty($email)) {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        } elseif (isEmailExists($email)){
            $errors['email'] = 'Email đã tồn tại.';
        };
    
        // Kiểm tra số điện thoại (Việt Nam: 10 số, bắt đầu bằng 03,05,07,08,09)
        if (empty($phoneN)) {
            $errors['phoneN'] = 'Số điện thoại không được để trống.';
        } elseif (!preg_match('/^(03|05|07|08|09)\d{8}$/', $phoneN)) {
            $errors['phoneN'] = 'Số điện thoại không hợp lệ.';
        }
    
        // Kiểm tra mật khẩu
        if (empty($password)) {
            $errors['password'] = 'Mật khẩu không được để trống.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 8 ký tự.';
        } elseif (!preg_match('/[A-Z]/', $password) ||
                  !preg_match('/[a-z]/', $password) ||
                  !preg_match('/[0-9]/', $password) ||
                  !preg_match('/[\W]/', $password)) {
            $errors['password'] = 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt.';
        }
    
        // Nếu có lỗi thì lưu lỗi + dữ liệu cũ vào session và quay lại trang đăng ký
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = [
                'username' => $username,
                'email' => $email,
                'phoneN' => $phoneN
            ];
            $_SESSION['active_form'] = 'register';
            header("Location: ?url=login");
            exit;
        }
    
        // Nếu hợp lệ -> mã hóa mật khẩu và thêm khách hàng
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $ip = $_SERVER['REMOTE_ADDR']; // Lấy IP của người dùng
        session_regenerate_id(true);
        $_SESSION['customer_id'] = addCustomer($username, $email, $phoneN, $hashedPassword, $ip);

        $cart_items = getCart($_SESSION['customer_id']); // Lấy danh sách sản phẩm từ DB
        $_SESSION['cart'] = []; // Khởi tạo mảng giỏ hàng
        foreach ($cart_items as $item) {
            if($item['trang_thai'] == 'in_cart')
            {$_SESSION['cart'][$item['san_pham_id']] = [
                'product_id' => $item['san_pham_id'],
                'quantity' => $item['so_luong'],
                'status' => true,
            ];}
        }
    
        header("Location: ?url=home");
        exit;
    }
    
}
