<?php 
require_once dirname(__DIR__, 2) . '/config/database.php';

function addCart($customer_id, $product_id, $quantity) {
    try {
        $conn = connection();

        $query = "INSERT INTO gio_hang (khach_hang_id, san_pham_id, so_luong) 
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$customer_id, $product_id, $quantity]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateQuantityCart($customer_id, $product_id, $quantity) {
    try {
        $conn = connection();

        $query = "UPDATE gio_hang SET so_luong = ? 
                  WHERE khach_hang_id = ? AND san_pham_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$quantity, $customer_id, $product_id]);

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

function updateStatusCart($customer_id, $product_id, $status) {
    try {
        $conn = connection();

        $query = "UPDATE gio_hang 
                  SET trang_thai = :status 
                  WHERE khach_hang_id = :customer_id 
                    AND san_pham_id = :product_id";

        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':status' => $status,
            ':customer_id' => $customer_id,
            ':product_id' => $product_id
        ]);

        return $stmt->rowCount() > 0; // trả true nếu có dòng bị ảnh hưởng
    } catch (PDOException $e) {
        // Ghi log nếu muốn theo dõi lỗi (log ra file hoặc echo khi debug)
        // error_log($e->getMessage());
        return false;
    }
}


function getCart($customer_id) {
    try {
        $conn = connection();

        $query = "SELECT * FROM gio_hang WHERE khach_hang_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$customer_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
