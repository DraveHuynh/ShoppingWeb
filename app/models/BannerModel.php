<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addBanner($url) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO

        // Câu SQL đúng
        $sql = "INSERT INTO banner_img (img_url) VALUES (:url)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':url' => $url]);

        return true;
    } catch (PDOException $e) {
        die("Lỗi khi thêm dữ liệu: " . $e->getMessage());
    }
}

function getAllBanners() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM banner_img";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function deleteBanner($id) {
    try {
        $conn = connection(); // Kết nối CSDL
        $query = "DELETE FROM banner_img WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Trả về true nếu xóa thành công
    } catch (PDOException $e) {
        return false; // Nếu có lỗi, trả về false
    }
}
