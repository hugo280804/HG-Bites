<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'food');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}
$conn->set_charset("utf8");

$sql = "SELECT id, ten, anh, gia, ghi_chu FROM sanpham";
$result = $conn->query($sql);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'ten' => $row['ten'],
            'anh' => $row['anh'],
            'gia' => (float)$row['gia'],
            'ghi_chu' => $row['ghi_chu'] ?? ''
        ];
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
