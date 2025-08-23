<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

function getAllCustomers() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM khach_hang";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getCustomer($id) {
    try{
        $conn = connection();
        $stmt = $conn->prepare("SELECT * FROM khach_hang WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false; // Nếu có lỗi, trả về mảng rỗng
    }
    
}

function addCustomer($username, $email, $phoneN, $password, $ip) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO
        
        $query = "INSERT INTO khach_hang (username, email, phone_number, password, ip_address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username, $email, $phoneN, $password, $ip]);

        return $conn->lastInsertId();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function isEmailExists($email) {
    $conn = connection();
    $stmt = $conn->prepare("SELECT 1 FROM khach_hang WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetch() !== false;
}

function isLoginValid($email, $password) {
    $conn = connection();
    $stmt = $conn->prepare("SELECT id, password FROM khach_hang WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        return $row['id']; // Đúng mật khẩu => trả về ID
    }

    return ''; // Sai => trả về chuỗi rỗng
}

function updateNameAndPhone($id, $username, $phone) {
    $conn = connection();
    $sql = "UPDATE khach_hang SET username = :username, phone_number = :phone WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':username' => $username,
        ':phone' => $phone
    ]);
}

function updatePassword($id, $password) {
    $conn = connection();
    $sql = "UPDATE khach_hang SET password = :password WHERE id = :id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':id' => $id,
        ':password' => $password,
    ]);
}

?>
