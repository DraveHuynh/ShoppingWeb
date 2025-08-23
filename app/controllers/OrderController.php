<?php
require_once ROOT_PATH.'/app/models/AddressModel.php';
require_once ROOT_PATH.'/app/models/ProductModel.php';
require_once ROOT_PATH.'/app/models/OrderModel.php';
require_once ROOT_PATH.'/app/models/CategoryModel.php';

class OrderController {
    public function index() {
        $title = "Đơn hàng";

        $view = ROOT_PATH.'/app/views/order.php';

        $customer_id = $_SESSION['customer_id'];

        $orders = getAllOrderByCId($customer_id);

        

        $orders_info = [];

        foreach ($orders as $order) {
            $order_details = getAllOrderDetailsByOId($order['id']);
            $address = getAllAddressByAId($order['dia_chi_id']);
            $trang_thai_map = [
                'Unconfirmed' => 'Chưa xác nhận',
                'Confirmed' => 'Đã xác nhận',
                'Cancel' => 'Đã hủy'
            ];
            $trang_thai = $trang_thai_map[$order['trang_thai']] ?? 'Không xác định';

            $san_pham = [];

            foreach ($order_details as $order_detail) {
                $product = getProduct($order_detail['san_pham_id']);
                $category = getCategory($product['danh_muc_id']);

                $san_pham[] = [
                    'ten_san_pham' => $product['ten'],
                    'hinh_anh' => $product['img_url'],
                    'ten_danh_muc' => $category['ten'],
                    'so_luong' => $order_detail['so_luong'],
                    'don_gia' => $order_detail['don_gia'],
                ];
            }

            $orders_info[] = [
                'don_hang_id' => $order['id'],
                'tong_tien' => $order['tong_tien'],
                'trang_thai' => $trang_thai,
                'dia_chi_id' => $order['dia_chi_id'],
                'san_pham' => $san_pham,
            ];
        }

        $addresses = getAllAddressByCId($customer_id);

        $ds_dia_chi = [];

        foreach ($addresses as $address) {
            $quan = getDistrictName($address['quan_id']);

            $ds_dia_chi[] = [
                'dia_chi_id' => $address['id'],
                'quan' => $quan['ten'],
                'so_nha_va_duong' => $address['so_nha_va_duong'],
                'sdt' => $address['sdt']
            ];
        }

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }

    public function update() {
        $don_hang_id = $_POST['don_hang_id'];
        $don_hang = getOrderByOId($don_hang_id);
        $dia_chi_id = $_POST['dia_chi_id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            if ($dia_chi_id != $don_hang['dia_chi_id']){
                updateOrder_AddressId($don_hang_id, $dia_chi_id);
            }
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])){
            $trang_thai = 'Cancel';
            updateOrder_Status($don_hang_id, $trang_thai);
        }

        header("Location: " . BASE_URL . "/index.php?url=order");
        exit();
    }
}
