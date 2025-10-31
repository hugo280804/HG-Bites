<?php
include __DIR__ . '/../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<script>alert('ID không hợp lệ!'); window.location.href='admin.php?page=cuahang';</script>");
}
$id = (int)$_GET['id'];

// Debug ID
// echo "ID cần xoá: " . $id; exit;

$sql = "DELETE FROM cuahang WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Lỗi prepare: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<script>alert('Đã xóa cửa hàng thành công!'); window.location.href='admin.php?page=cuahang';</script>";
} else {
    echo "<script>alert('Không thể xóa cửa hàng! Có thể ID không tồn tại hoặc bị ràng buộc khóa ngoại.'); window.location.href='admin.php?page=cuahang';</script>";
}
$stmt->close();
$conn->close();
?>
