<?php
require_once ROOT_PATH.'/app/models/AddressModel.php';


class AddressController {
    public function index() {
        $title = "Thông tin địa chỉ";

        $view = ROOT_PATH.'/app/views/address.php';

        $customer_id = $_SESSION['customer_id'];

        $addresses_info = getAllAddressByCId($customer_id);

        $addresses = [];

        foreach ($addresses_info as $value) {
            $district = getDistrictName($value['quan_id']);

            $addresses[] = [
                'dia_chi_id' => $value['id'],
                'so_nha_va_duong' => $value['so_nha_va_duong'],
                'quan' => $district['ten'],
                'sdt' => $value['sdt'],
            ];
        }

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php';
    }

    public function action() {

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
            $dia_chi_id = $_POST['dia_chi_id'];

            deleteAddressById($dia_chi_id);
            header("Location: " . BASE_URL . "/index.php?url=address");
            exit();
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $dia_chi_id = $_POST['dia_chi_id'];
            return $this->form($dia_chi_id);
        }
  
    }

    public function form($dia_chi_id = null) {
        $title = "Thêm địa chỉ";

        $view = ROOT_PATH.'/app/views/form_address.php';

        if ($dia_chi_id) {
           $address = getAllAddressByAId($dia_chi_id);
           if ($address) {
            $title = "Sửa địa chỉ";
           }
        }

        $ds_quan = getAllDistricts();

        $errors = [];

        if (isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        }


        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php';
    }

    public function checkAddress() {
        // Lấy dữ liệu từ form
        $khach_hang_id = $_SESSION['customer_id'];
        $dia_chi_id = $_POST['dia_chi_id'] ?? '';
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
            header("Location: " . BASE_URL . "/index.php?url=address/form");
            exit();
        }

        // Nếu không có lỗi, tiếp tục hiển thị trang xác nhận thanh toán
        if ($dia_chi_id) {
            updateAddress($dia_chi_id, $district, $street, $phone);
        } else{
            addAddress($khach_hang_id, $district, $street, $phone);
        }
        header("Location: " . BASE_URL . "/index.php?url=address");
        exit();
    }
}
