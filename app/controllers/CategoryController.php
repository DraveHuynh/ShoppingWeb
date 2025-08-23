<?php
require_once dirname(__DIR__, 2)."/config/init.php";
require_once ROOT_PATH."/app/models/CategoryModel.php";

// Xử lý thêm danh mục
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Add'])) {
    $name = $_POST['name'];

    if($name){
        if (addCategory($name)) {
            header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Categorys"); // Chuyển về danh sách danh mục
            exit();
        } else {
            echo "Lỗi khi thêm danh mục!";
        }
    }
    else {
        header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Categorys");
        exit();
    }
}

// Xử lý cập nhật danh mục
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
    $id = $_POST['id'];
    $category_name = $_POST['category_name'];

    if (updateCategory($id, $category_name)) {
        header("Location: " . BASE_URL . "/N2pQo6CoUp4GAZ_vR_BiNw/index.php?page=Categorys");
        exit();
    }
}

