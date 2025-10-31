<?php
include __DIR__ . '/../includes/db.php';

// Kiểm tra ID hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<script>alert('ID danh mục không hợp lệ!'); window.location.href='admin.php?page=danhsachdanhmuc';</script>");
}

$maDanhMuc = (int)$_GET['id'];

// Kiểm tra danh mục có tồn tại không
$check = $conn->query("SELECT * FROM DanhMuc WHERE MaDanhMuc=$maDanhMuc");
if (!$check) {
    die("<script>alert('Lỗi truy vấn CSDL!'); window.location.href='admin.php?page=danhsachdanhmuc';</script>");
}

if ($check->num_rows === 0) {
    die("<script>alert('Danh mục không tồn tại!'); window.location.href='admin.php?page=danhsachdanhmuc';</script>");
}

// Xóa danh mục
$sql = "DELETE FROM DanhMuc WHERE MaDanhMuc=$maDanhMuc";

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Xóa danh mục thành công!');
        window.location.href = 'admin.php?page=danhsachdanhmuc';
    </script>";
} else {
    echo "<script>
        alert('Không thể xóa danh mục. Lỗi: " . $conn->error . "');
        window.location.href = 'admin.php?page=danhsachdanhmuc';
    </script>";
}

$conn->close();
?>
