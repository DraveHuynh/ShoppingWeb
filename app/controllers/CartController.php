<?php
require_once ROOT_PATH.'/app/models/ProductModel.php';
require_once ROOT_PATH.'/app/models/CategoryModel.php';
require_once ROOT_PATH.'/app/models/CartModel.php';
require_once ROOT_PATH.'/app/models/AddressModel.php';
require_once ROOT_PATH.'/app/models/OrderModel.php';

class CartController {
    public function index() {
        $title = "Giỏ hàng";
        $view = ROOT_PATH.'/app/views/cart.php';

        $cartItems = [];
        $sessionCart = $_SESSION['cart'] ?? [];

        foreach ($sessionCart as $product_id => $cartData) {
            $product = getProduct($product_id); // Lấy thông tin sản phẩm
            $category = getCategory($product['danh_muc_id']); // Lấy tên danh mục
    
            $cartItems[] = [
                'ten_san_pham' => $product['ten'],
                'ten_danh_muc' => $category['ten'],
                'gia' => $product['gia'],
                'img_url' => $product['img_url'],
                'quantity' => $cartData['quantity'],
                'product_id' => $product_id, // để tiện xử lý cập nhật/xoá
                'status' => $cartData['status'],
            ];
        }
        
        $ds_quan = getAllDistricts();
        $dia_chi = getAllAddressByCId($_SESSION['customer_id']);
        
        $ds_dia_chi = [];

        foreach ($dia_chi as  $value) {
            $quan = getDistrictName($value['quan_id']);

            $ds_dia_chi[] = [
                'dia_chi_id' => $value['id'],
                'so_nha_va_duong' => $value['so_nha_va_duong'],
                'ten_quan' => $quan['ten'],
                'sdt' => $value['sdt'],
            ];
        }

        $errors = [];

        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chínhdữ
    }

    public function addCart() {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
        // Nhận dữ liệu từ AJAX nếu có
        if ($isAjax) {
            $data = json_decode(file_get_contents('php://input'), true);
            $_POST = $data ?? []; 
        }
    
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            $customer_id = $_SESSION['customer_id'];
    
            $product = getProduct($productId); // Lấy thông tin sản phẩm từ DB
    
            if ($product) {
                // Nếu chưa có sản phẩm trong giỏ hàng
                if (!isset($_SESSION['cart'][$productId])) {
                    $bool = addCart($customer_id, $productId, $quantity);
                    if ($bool) {
                        $_SESSION['cart'][$productId] = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'status' => true,
                        ];
    
                        if ($isAjax) {
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                            ]);
                            exit();
                        } else {
                            header("Location: " . BASE_URL . "/index.php?url=product_detail&product_id=$productId");
                            exit();
                        }
                    }
    
                    if ($isAjax) {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Thêm dữ liệu thất bại',
                        ]);
                        exit();
                    } else {
                        header("Location: " . BASE_URL . "/index.php?url=product_detail&product_id=$productId");
                        exit();
                    }
                } 
                // Nếu đã có trong giỏ hàng thì cập nhật số lượng
                else {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
    
                    $bool = updateQuantityCart($customer_id, $productId, $_SESSION['cart'][$productId]['quantity']);
    
                    if ($bool) {
                        if ($isAjax) {
                            echo json_encode([
                                'status' => 'success',
                                'message' => 'Sản phẩm đã thêm vào giỏ hàng',
                            ]);
                            exit();
                        } else {
                            header("Location: " . BASE_URL . "/index.php?url=product_detail&product_id=$productId");
                            exit();
                        }
                    }
    
                    // Nếu cập nhật thất bại, khôi phục lại số lượng
                    $_SESSION['cart'][$productId]['quantity'] -= $quantity;
    
                    if ($isAjax) {
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Cập nhật thất bại',
                        ]);
                        exit();
                    } else {
                        header("Location: " . BASE_URL . "/index.php?url=product_detail&product_id=$productId");
                        exit();
                    }
                }
            }
    
            // Trường hợp không tìm thấy sản phẩm
            if ($isAjax) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Sản phẩm không có thông tin',
                ]);
                exit();
            } else {
                header("Location: " . BASE_URL . "/index.php?url=product_detail&product_id=$productId");
                exit();
            }
        }
    
        // Trường hợp không nhận được dữ liệu hợp lệ
        if ($isAjax) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gửi không thành công',
            ]);
            exit();
        } else {
            // Chuyển về trang chủ hoặc thông báo lỗi khác nếu cần
            header("Location: " . BASE_URL . "/index.php?url=home");
            exit();
        }
    }

    public function updateCart(){
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
        // Nhận dữ liệu từ AJAX nếu có
        if ($isAjax) {
            $data = json_decode(file_get_contents('php://input'), true);
            $_POST = $data ?? []; 
        }

        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            $customer_id = $_SESSION['customer_id'];

            $_SESSION['cart'][$productId]['quantity'] = $quantity;

            updateQuantityCart($customer_id, $productId, $_SESSION['cart'][$productId]['quantity']);
        }
    }

    public function removeCart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit;
        }
    
        $productId = intval($_POST['product_id']);
        $customer_id = $_SESSION['customer_id'] ?? null;
    
        if (!$customer_id || !isset($_SESSION['cart'][$productId])) {
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit;
        }
    
        // Cập nhật trạng thái trong DB
        if (updateStatusCart($customer_id, $productId, 'removed')) {
            // Đồng bộ session nếu DB xử lý ok
            unset($_SESSION['cart'][$productId]);
        }
    
        // Quay về trang giỏ
        header("Location: " . BASE_URL . "/index.php?url=cart");
        exit;
    }
    
    public function toggleStatus() {
        // Đọc dữ liệu JSON thủ công từ php://input
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['product_id']) || !isset($_SESSION['cart'][$data['product_id']])) {
            echo json_encode(['status' => 'error']);
            exit;
        }
    
        $productId = intval($data['product_id']);
        $_SESSION['cart'][$productId]['status'] = isset($data['status']) && $data['status'] ? true : false;
    
        echo json_encode(['status' => 'success']);
        exit;
    }

    public function getSummary() {
        // Nếu không phải yêu cầu AJAX (có thể là do JS bị lỗi hoặc client gửi request trực tiếp)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            // Redirect về trang giỏ hàng để reload trang
            header('Location: ' . BASE_URL . '/index.php?url=cart');
            exit;
        }
    
        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
    
        foreach ($cart as $product_id => $cartData) {
            if (!isset($cartData['status']) || $cartData['status'] !== true) {
                continue; // Chỉ tính các sản phẩm được chọn
            }
    
            $product = getProduct($product_id);
            if (!$product) continue; // Phòng trường hợp sản phẩm đã bị xóa khỏi DB
    
            $quantity = $cartData['quantity'] ?? 1;
            $price = $product['gia'] ?? 0;
    
            $subtotal += $price * $quantity;
        }
    
        $total = $subtotal; // Có thể thêm phí ship, thuế ở đây nếu cần
    
        // Trả về kết quả AJAX
        echo json_encode([
            'subtotal' => $subtotal,
            'total' => $total,
        ]);
        exit;
    }
    
    public function checkAddress() {
        // Lấy dữ liệu từ form
        $district = $_POST['district'] ?? '';
        $street = trim($_POST['street'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];

        // Kiểm tra quận
        if (empty($district)) {
            $errors['district'] = "Vui lòng chọn quận.";
        }

        // Kiểm tra địa chỉ
        if (empty($street)) {
            $errors['street'] = "Vui lòng nhập địa chỉ.";
        }

        // Kiểm tra địa chỉ
        if (empty($phone)) {
            $errors['phone'] = "Vui lòng nhập số điện thoại.";
        } elseif (!preg_match('/^0\d{9}$/', $phone)) {
            $errors['phone'] = "Số điện thoại không hợp lệ.";
        }

        

        // Nếu có lỗi thì lưu vào session và quay lại trang giỏ hàng
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit();
        }

        // Nếu không có lỗi, tiếp tục hiển thị trang xác nhận thanh toán
        return $this->addAddress($district, $street, $phone);
    }

    public function addAddress($district = null, $street = null, $phone = null) {

        $khach_hang_id = $_SESSION['customer_id'];

        // Nếu form gửi lên đã có sẵn dia_chi_id → dùng ngay, không cần tạo mới
        if (isset($_POST['dia_chi_id']) && !empty($_POST['dia_chi_id'])) {
            $dia_chi_id = $_POST['dia_chi_id'];
            return $this->confirmPayment($dia_chi_id);
        }

        // Trường hợp tạo mới địa chỉ
        if ($district && $street && $phone) {
            $dia_chi_id = addAddress($khach_hang_id, $district, $street, $phone);

            if ($dia_chi_id) {
                return $this->confirmPayment($dia_chi_id);
            }
        }
        
    }
    
    public function confirmPayment($dia_chi_id) {
        // Kiểm tra xem khách hàng đã đăng nhập chưa
        $khach_hang_id = $_SESSION['customer_id'] ?? null;
        if (!$khach_hang_id) {
            // Nếu không có session khách hàng, chuyển hướng về trang đăng nhập
            header("Location: " . BASE_URL . "/index.php?url=login");
            exit();
        }
    
        // Kiểm tra giỏ hàng có sản phẩm không
        if (empty($_SESSION['cart'])) {
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit();
        }
    
        $cart = $_SESSION['cart'];
        // Lọc các sản phẩm đã chọn để thanh toán
        $items_to_pay = array_filter($cart, fn($item) => $item['status'] === true);
    
        // Nếu không có sản phẩm nào được chọn để thanh toán
        if (empty($items_to_pay)) {
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit();
        }
    
        // Tính tổng tiền
        $tong_tien = 0;
        foreach ($items_to_pay as $item) {
            // Lấy thông tin sản phẩm từ CSDL
            $product = getProduct($item['product_id']); // Lấy thông tin sản phẩm từ cơ sở dữ liệu
            $_SESSION['test_data'][$item['product_id']] = $item['product_id'];
            if ($product) {
                $price = (int) $product['gia']; // Giả sử "gia" là giá của sản phẩm
                $tong_tien += ((int)$item['quantity']) * $price; // Tính tổng tiền
            }
        }
    
        // Nếu tổng tiền <= 0, chuyển hướng về giỏ hàng
        if ($tong_tien <= 0) {
            header("Location: " . BASE_URL . "/index.php?url=home");
            exit();
        }
        var_dump($khach_hang_id, $dia_chi_id, $tong_tien, $items_to_pay);
        exit();
        try {
            // 1. Tạo đơn hàng
            $don_hang_id = addOrder($khach_hang_id, $dia_chi_id, $tong_tien);
    
            // 2. Thêm chi tiết đơn hàng vào CSDL
            foreach ($items_to_pay as $item) {
                $product = getProduct($item['product_id']); // Lấy lại thông tin sản phẩm
                if ($product) {
                    $price = (int) $product['gia']; // Lấy giá của sản phẩm
                    $quantity = (int) $item['quantity'];
                    $thanh_tien = $quantity * $price;
                    addOrderDetail($don_hang_id, $item['product_id'], $quantity, $price, $thanh_tien);
                }
            }
    
            // 3. Cập nhật lại giỏ hàng trong session: xóa sản phẩm đã thanh toán
            foreach ($items_to_pay as $id => $item) {
                updateStatusCart($khach_hang_id, $id, 'ordered');
                unset($_SESSION['cart'][$id]); // Xoá theo key sản phẩm đã thanh toán
            }
    
            // 4. Chuyển hướng đến trang thành công
            header("Location: " . BASE_URL . "/index.php?url=cart"); //Đường dẫn tạm thời 
            exit();
        } catch (Exception $e) {
            // Nếu có lỗi trong quá trình tạo đơn hàng, chuyển hướng đến trang lỗi
            header("Location: " . BASE_URL . "/index.php?url=cart");
            exit();
        }
    }
    
}