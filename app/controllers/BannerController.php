<?php
require_once dirname(__DIR__, 2)."/config/init.php";
require_once ROOT_PATH."/app/models/BannerModel.php";

// Xử lý thêm danh mục
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add'])) {
    $uploadDir = ROOT_PATH.'/public/assets/images/';

    $photoPath = '';

    // Xử lý ảnh bìa
    if (!empty($_FILES['url']['name'])) {
        $bannerName = uniqid() . "_" . basename($_FILES['url']['name']);
        $photoPath = $uploadDir . $bannerName;

        if (!move_uploaded_file($_FILES['url']['tmp_name'], $photoPath)) {
            echo "Lỗi khi tải ảnh bìa!<br>";
            $photoPath = ''; // Tránh lưu đường dẫn sai
        } else addBanner($bannerName);
    }
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Banners");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['Delete'])) {
    $banner_id = isset($_POST['banner_id']) ? (int)$_POST['banner_id'] : 0;

    if ($banner_id > 0) {
        $deleted = deleteBanner($banner_id);
    }

    // Điều hướng lại sau xử lý
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Banners");
    exit;
}
