<?php
header('Content-Type: application/json; charset=utf-8');

// Kết nối database
$conn = new mysqli('localhost', 'root', '', 'food'); // đổi theo tên DB của bạn
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}
$conn->set_charset("utf8");

// Lấy dữ liệu danh mục
$sql = "SELECT MaDanhMuc, TenDanhMuc, MoTa FROM danhmuc ORDER BY MaDanhMuc ASC";
$result = $conn->query($sql);

$categories = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            'id' => $row['MaDanhMuc'],
            'ten' => $row['TenDanhMuc'],
            'mo_ta' => $row['MoTa'] ?? ''
        ];
    }
}

// Xuất JSON
echo json_encode($categories, JSON_UNESCAPED_UNICODE);

$conn->close();
?>