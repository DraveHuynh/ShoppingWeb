<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addOrder($khach_hang_id, $dia_chi_id, $tong_tien, $ghi_chu = '') {
    try {
        $conn = connection();
        if (!$conn) {
            throw new Exception("Không thể kết nối CSDL");
        }

        $query = "INSERT INTO don_hang (khach_hang_id, tong_tien, dia_chi_id, ghi_chu)
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$khach_hang_id, $tong_tien, $dia_chi_id, $ghi_chu]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("DB Error (Order): " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("General Error (Order): " . $e->getMessage());
        return false;
    }
}

function addOrderDetail($don_hang_id, $san_pham_id, $so_luong, $don_gia, $thanh_tien) {
    try {
        $conn = connection();
        if (!$conn) {
            throw new Exception("Không thể kết nối CSDL");
        }

        $query = "INSERT INTO chi_tiet_don_hang (don_hang_id, san_pham_id, so_luong, don_gia, 	thanh_tien)
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$don_hang_id, $san_pham_id, $so_luong, $don_gia, $thanh_tien]);

        return true;
    } catch (PDOException $e) {
        error_log("DB Error (Order Detail): " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        error_log("General Error (Order Detail): " . $e->getMessage());
        return false;
    }
}

function getAllOrders() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM don_hang";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getAllOrderByCId($khach_hang_id) {
    
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM don_hang WHERE khach_hang_id = :khach_hang_id");
        $stmt->bindParam(':khach_hang_id', $khach_hang_id);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getOrderByOId($don_hang_id) {
    
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM don_hang WHERE id = :id");
        $stmt->bindParam(':id',  $don_hang_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getAllOrderDetailsByOId($don_hang_id) {
    
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM chi_tiet_don_hang WHERE don_hang_id = :don_hang_id");
        $stmt->bindParam(':don_hang_id', $don_hang_id);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function updateOrder_AddressId($don_hang_id, $dia_chi_id) {
    try {
    $conn = connection();
    $sql = "UPDATE don_hang SET dia_chi_id = :dia_chi_id WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $don_hang_id,
        ':dia_chi_id' => $dia_chi_id,
    ]);
} catch (PDOException $e) {
    return false; // Nếu có lỗi, trả về mảng rỗng
}
}

function updateOrder_Status($don_hang_id, $trang_thai) {
    $conn = connection();
    $sql = "UPDATE don_hang SET trang_thai = :trang_thai WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $don_hang_id,
        ':trang_thai' => $trang_thai,
    ]);
}

?>
