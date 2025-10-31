<?php
include __DIR__ . '/../includes/db.php';

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID sản phẩm không hợp lệ!");
}

$id = (int)$_GET['id'];

// Kiểm tra sản phẩm tồn tại
$check = $conn->query("SELECT * FROM sanpham WHERE id=$id");
if(!$check) die("Lỗi truy vấn SELECT: " . $conn->error);

if($check->num_rows === 0) {
    die("Sản phẩm không tồn tại!");
}

// Xóa sản phẩm
$sql = "DELETE FROM sanpham WHERE id=$id";
if(!$conn->query($sql)) {
    die("Lỗi DELETE: " . $conn->error);
}

if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Xóa danh mục thành công!');
        window.location.href = 'admin.php?page=danhsachsanpham';
    </script>";
} else {
    echo "<script>
        alert('Không thể xóa danh mục. Lỗi: " . $conn->error . "');
        window.location.href = 'admin.php?page=danhsachsanpham';
    </script>";
}

?>
