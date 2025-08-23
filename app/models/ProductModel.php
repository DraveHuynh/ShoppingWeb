<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addProduct($ten, $mo_ta, $url_anh, $danh_muc_id, $gia) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO
        
        $query = "INSERT INTO san_pham (ten, mo_ta, img_url, danh_muc_id, gia) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$ten, $mo_ta, $url_anh, $danh_muc_id, $gia]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        die("Lỗi khi thêm dữ liệu: " . $e->getMessage());
    }
}

function getAllProducts() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM san_pham";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getProduct($id) {
    $conn = connection();
    $stmt = $conn->prepare("SELECT * FROM san_pham WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductsByCategory($categoryId) {
    
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM san_pham WHERE danh_muc_id = :danh_muc_id");
        $stmt->bindParam(':danh_muc_id', $categoryId);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

// Hàm cập nhật nhân viên
function updateProduct($id, $ten, $mo_ta, $danh_muc_id, $gia, $trang_thai) {
    $conn = connection();
    $sql = "UPDATE san_pham SET ten = :ten, mo_ta = :mo_ta, danh_muc_id = :danh_muc_id, gia = :gia, trang_thai = :trang_thai WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':ten' => $ten,
        ':mo_ta' => $mo_ta,
        ':danh_muc_id' => $danh_muc_id,
        ':gia' => $gia,
        ':trang_thai' => $trang_thai,

    ]);
}
// Hàm xóa nhân viên
function deleteProduct($id, $trang_thai) {
    $conn = connection();
    $sql = "UPDATE san_pham SET trang_thai = :trang_thai WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':trang_thai' => $trang_thai
    ]);
}

function updatePrice($id, $gia) {
    $conn = connection();
    $sql = "UPDATE san_pham SET gia = :gia WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':gia' => $gia
    ]);
}
?>
