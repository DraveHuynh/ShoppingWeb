<?php
require_once ROOT_PATH.'/app/models/CustomerModel.php';


class UserController {
    public function index() {
        $title = "Thông tin người dùng";

        $view = ROOT_PATH.'/app/views/user.php';

        $customer = getCustomer($_SESSION['customer_id']);

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }

    public function update() {

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $id = $_SESSION['customer_id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            updateNameAndPhone($id, $username, $phone);
        }

        header("Location: " . BASE_URL . "/index.php?url=user");
    }
}
