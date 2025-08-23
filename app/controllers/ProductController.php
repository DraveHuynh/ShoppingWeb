<?php
require_once dirname(__DIR__, 2)."/config/init.php";
require_once ROOT_PATH."/app/models/ProductModel.php";
require_once ROOT_PATH."/app/models/ImagesProductModel.php";

// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add'])) {

    // Lấy dữ liệu từ $_POST
    $ten = $_POST['products_name'] ?? '';
    $mo_ta = $_POST['product_description'] ?? '';
    $danh_muc_id = $_POST['category_id'] ?? 0;
    $gia = $_POST['price'] ?? 0;

    // Đường dẫn thư mục lưu ảnh
    $uploadDir = ROOT_PATH.'/public/assets/images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Mảng lưu đường dẫn ảnh chi tiết
    $productImages = [];
    $coverPhotoPath = ''; // Nếu không có ảnh bìa thì để trống

    // Xử lý ảnh bìa
    if (!empty($_FILES['cover_photo']['name'])) {
        $coverFileName = uniqid() . "_" . basename($_FILES['cover_photo']['name']);
        $coverPhotoPath = $uploadDir . $coverFileName;

        if (!move_uploaded_file($_FILES['cover_photo']['tmp_name'], $coverPhotoPath)) {
            echo "Lỗi khi tải ảnh bìa!<br>";
            $coverPhotoPath = ''; // Tránh lưu đường dẫn sai
        }
    }

    // Xử lý ảnh chi tiết
    if (!empty($_FILES['product_images']['name'][0])) {
        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['product_images']['error'][$key] == UPLOAD_ERR_OK) {
                $fileName = uniqid() . "_" . basename($_FILES['product_images']['name'][$key]);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    $productImages[] = $fileName; // Lưu đường dẫn ảnh chi tiết
                } else {
                    echo "Lỗi khi tải ảnh $fileName!<br>";
                }
            }
        }
    }

    // Thêm sản phẩm vào database
    $last_product_id = addProduct($ten, $mo_ta, $coverFileName, $danh_muc_id, $gia);

    if (!$last_product_id) {
        echo "Lỗi khi thêm sản phẩm!<br>";
        exit();
    }

    // Nếu có ảnh chi tiết mới thêm vào CSDL
    if (!empty($productImages)) {
        foreach ($productImages as $productImage) {
            addImage($last_product_id, $productImage);
        }
    }

    // Chuyển hướng sau khi thêm thành công
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Products");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])){
    // Lấy dữ liệu từ $_POST
    $id = $_POST['id'] ?? Null;
    $ten = $_POST['products_name'] ?? '';
    $mo_ta = $_POST['product_description'] ?? '';
    $danh_muc_id = $_POST['category_id'] ?? 0;
    $gia = $_POST['price'] ?? 0;
    $trang_thai = $_POST['status'] ?? '0';

    if ($id != Null) {
        updateProduct($id, $ten, $mo_ta, $danh_muc_id, $gia, $trang_thai);
    }
    // Chuyển hướng sau khi sửa
    header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Products");
    exit();
}

