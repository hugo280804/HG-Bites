<?php
header('Content-Type: application/json; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'food');
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Không kết nối được database']);
    exit;
}
$conn->set_charset("utf8");

// Lấy tham số từ URL
$keyword = $_GET['keyword'] ?? '';
$danhmuc = $_GET['danhmuc'] ?? 'all';

if ($keyword !== '') {
    // 🔍 Tìm kiếm theo từ khóa
    $keywordLike = "%$keyword%";
    $sql = "SELECT sp.id, sp.ten, sp.anh, sp.gia, sp.ghi_chu, dm.tendanhmuc
            FROM sanpham sp
            JOIN danhmuc dm ON sp.danh_muc = dm.MaDanhMuc
            WHERE sp.ten LIKE ? OR dm.tendanhmuc LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $keywordLike, $keywordLike);
    $stmt->execute();
    $result = $stmt->get_result();

} else if ($danhmuc === 'all') {
    // 🧾 Lấy tất cả sản phẩm
    $sql = "SELECT sp.id, sp.ten, sp.anh, sp.gia, sp.ghi_chu, dm.tendanhmuc
            FROM sanpham sp
            JOIN danhmuc dm ON sp.danh_muc = dm.MaDanhMuc";
    $result = $conn->query($sql);

} else {
    // 🧩 Lọc sản phẩm theo danh mục
    $sql = "SELECT sp.id, sp.ten, sp.anh, sp.gia, sp.ghi_chu, dm.tendanhmuc
            FROM sanpham sp
            JOIN danhmuc dm ON sp.danh_muc = dm.MaDanhMuc
            WHERE dm.MaDanhMuc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $danhmuc);
    $stmt->execute();
    $result = $stmt->get_result();
}

// 🧾 Xử lý kết quả
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'ten' => $row['ten'],
            'anh' => $row['anh'],
            'gia' => (float)$row['gia'],
            'ghi_chu' => $row['ghi_chu'] ?? '',
            'tendanhmuc' => $row['tendanhmuc'] ?? ''
        ];
    }
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
