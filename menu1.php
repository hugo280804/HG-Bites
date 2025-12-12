<?php
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "food");

if ($conn->connect_error) {
    die(json_encode(["error" => "Kết nối thất bại"]));
}

$conn->set_charset("utf8");

// Lấy đơn mới nhất
$sql = "SELECT diachi_khach, ngay_dat, gio_dat, loai_dat_hang, cuahang FROM dathang ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc(), JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không có đơn hàng"], JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>
