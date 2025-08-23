<?php
require_once dirname(__DIR__, 2)."/config/init.php";
require_once ROOT_PATH."/app/models/OrderModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['don_hang_id'])) {
    $don_hang_id = $_POST['don_hang_id'];
    $don_hang = getOrderByOId($don_hang_id);
    $dia_chi_id = $_POST['dia_chi_id'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $trang_thai = 'Confirmed';
        updateOrder_Status($don_hang_id, $trang_thai);
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])){
        $trang_thai = 'Cancel';
        updateOrder_Status($don_hang_id, $trang_thai);
    }
    
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Orders");

}
    