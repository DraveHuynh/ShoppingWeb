<?php
require_once dirname(__DIR__, 2) . '/config/database.php';


function getAllshowHome() {
    try {
        $conn = connection(); // Kết nối database
        $query = "SELECT * FROM showhome";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách nhân viên
    } catch (PDOException $e) {
        return []; // Nếu có lỗi, trả về mảng rỗng
    }
}

function getShowHome($id) {
    $conn = connection();
    $stmt = $conn->prepare("SELECT * FROM showhome WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addShowhome($tieu_de, $danh_muc_id) {
    try {
        $conn = connection(); // Gọi hàm để lấy kết nối PDO

        // Lấy số lượng dòng hiện tại để xác định STT kế tiếp
        $stmt = $conn->query("SELECT COUNT(*) AS total FROM showhome");
        $nextSTT = $stmt->fetch()['total'] + 1;

        // Thêm dữ liệu vào bảng
        $query = "INSERT INTO showhome (stt, tieu_de, danh_muc_id) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->execute([$nextSTT, $tieu_de, $danh_muc_id]);

        return true;
    } catch (PDOException $e) {
        error_log("Lỗi khi thêm showhome: " . $e->getMessage());
        return "Lỗi khi thêm showhome: " . $e->getMessage();;
    }
}

function updateShowhome($id, $tieu_de, $danh_muc_id) {
    try {
        $conn = connection(); // Gọi kết nối PDO

        $query = "UPDATE showhome SET tieu_de = ?, danh_muc_id = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$tieu_de, $danh_muc_id, $id]);

        return true;
    } catch (PDOException $e) {
        error_log("Lỗi khi cập nhật showhome: " . $e->getMessage());
        return "Lỗi khi cập nhật showhome: " . $e->getMessage();
    }
}

function deleteShowhome($id) {
    try {
        $conn = connection();

        // Xóa dòng có id tương ứng
        $query = "DELETE FROM showhome WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);

        // Reset lại STT cho toàn bộ bảng
        $resetQuery = "SET @row = 0";
        $conn->exec($resetQuery);

        $updateSTTQuery = "UPDATE showhome SET stt = (@row := @row + 1) ORDER BY id ASC";
        $conn->exec($updateSTTQuery);

        return true;
    } catch (PDOException $e) {
        error_log("Lỗi khi xoá showhome: " . $e->getMessage());
        return "Lỗi khi xoá showhome: " . $e->getMessage();
    }
}
