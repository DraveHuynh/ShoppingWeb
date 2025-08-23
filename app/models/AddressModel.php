<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function addAddress($khach_hang_id, $district, $street, $phone) {
    try {
        $conn = connection();
        if (!$conn) {
            throw new Exception("Không thể kết nối CSDL");
        }

        $query = "INSERT INTO dia_chi (khach_hang_id, so_nha_va_duong, quan_id, sdt) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$khach_hang_id, $street, $district, $phone]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        return false; // Trả về false để controller biết là có lỗi
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        return false;
    }
}

function getAllDistricts() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM quan";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getAllAddressByAId($id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM dia_chi WHERE id = :id AND trang_thai = 'true'";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Gán tham số
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về danh sách quận
    } catch (PDOException $e) {
        // Ghi log lỗi nếu cần thiết
        // error_log($e->getMessage());
        return []; // Trả về mảng rỗng nếu có lỗi
    }
}

function getAllAddressByAIdAdmin($id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM dia_chi WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Gán tham số
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về danh sách quận
    } catch (PDOException $e) {
        // Ghi log lỗi nếu cần thiết
        // error_log($e->getMessage());
        return []; // Trả về mảng rỗng nếu có lỗi
    }
}

function getAllAddressByCId($customer_id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM dia_chi WHERE khach_hang_id = :khach_hang_id AND trang_thai = 'true'";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':khach_hang_id', $customer_id, PDO::PARAM_INT); // Gán tham số
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách quận
    } catch (PDOException $e) {
        // Ghi log lỗi nếu cần thiết
        // error_log($e->getMessage());
        return []; // Trả về mảng rỗng nếu có lỗi
    }
}

function getDistrictName($quan_id) {
    try {
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM quan WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $quan_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về 1 bản ghi (hoặc false)
    } catch (PDOException $e) {
        // Ghi log nếu cần: error_log($e->getMessage());
        return false; // Trả về false nếu có lỗi
    }
}

function updateAddress($dia_chi_id, $district, $street, $phone) {
    try {
        $conn = connection();

        $query = "UPDATE dia_chi 
                  SET quan_id = :quan_id, so_nha_va_duong = :so_nha_va_duong, sdt = :sdt 
                  WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':quan_id' => $district,
            ':so_nha_va_duong' => $street,
            ':sdt' => $phone,
            ':id' => $dia_chi_id
        ]);

        return $stmt->rowCount() > 0; // trả về true nếu có dòng bị cập nhật
    } catch (PDOException $e) {
        // error_log($e->getMessage()); // Ghi log khi cần
        return false;
    }
}


function deleteAddressById($address_id) {
    try {
        $conn = connection(); // Kết nối database
        $query = "UPDATE dia_chi SET trang_thai = 'false' WHERE id = :dia_chi_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':dia_chi_id', $address_id, PDO::PARAM_INT);
        return $stmt->execute(); // Trả về true nếu cập nhật thành công
    } catch (PDOException $e) {
        // error_log($e->getMessage());
        return false;
    }
}

?>
