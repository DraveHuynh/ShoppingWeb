<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addImage($sp_id, $url_anh) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO
        
        $query = "INSERT INTO hinh_anh_san_pham (san_pham_id, img_url) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$sp_id, $url_anh]);

        return true;
    } catch (PDOException $e) {
        die("Lỗi khi thêm dữ liệu: " . $e->getMessage());
    }
}

function getImages($sp_id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM hinh_anh_san_pham WHERE san_pham_id = :sp_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sp_id', $sp_id, PDO::PARAM_INT); // Chỉ định kiểu dữ liệu là INT
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách hình ảnh sản phẩm
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn getImages: " . $e->getMessage()); // Ghi log lỗi
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getNameImages($sp_id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT img_url FROM hinh_anh_san_pham WHERE san_pham_id = :sp_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':sp_id', $sp_id, PDO::PARAM_INT); // Chỉ định kiểu dữ liệu là INT
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách hình ảnh sản phẩm
    } catch (PDOException $e) {
        error_log("Lỗi truy vấn getImages: " . $e->getMessage()); // Ghi log lỗi
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

?>
