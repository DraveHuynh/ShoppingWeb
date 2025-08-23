<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addCategory($name) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO

        // Câu SQL đúng
        $sql = "INSERT INTO danh_muc (ten) VALUES (:name)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':name' => $name]);

        return true;
    } catch (PDOException $e) {
        die("Lỗi khi thêm dữ liệu: " . $e->getMessage());
    }
}


function getAllCategorys() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM danh_muc";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getCategory($id) {
    $conn = connection();
    $stmt = $conn->prepare("SELECT * FROM danh_muc WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateCategory($id, $ten) {
    $conn = connection();
    $sql = "UPDATE danh_muc SET ten = :ten WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':ten' => $ten,
    ]);
}

