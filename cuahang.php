<?php
header('Content-Type: application/json; charset=utf-8');
$conn = new mysqli('localhost', 'root', '', 'food'); // đổi theo tên DB của bạn
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}
$conn->set_charset("utf8");
// Lấy dữ liệu cửa hàng do admin nhập
$sql = "SELECT id, ten, diachi, sdt, latitude, longitude FROM cuahang ORDER BY id ASC";
$result = $conn->query($sql);

$stores = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $stores[] = $row;
    }
}

echo json_encode($stores, JSON_UNESCAPED_UNICODE);
$conn->close();
