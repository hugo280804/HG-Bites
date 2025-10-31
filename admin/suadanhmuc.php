<?php
include __DIR__ . '/../includes/db.php';

// Kiểm tra tham số id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID danh mục không hợp lệ!");
}

$id = (int) $_GET['id'];

// Lấy thông tin danh mục
$sql = "SELECT * FROM danhmuc WHERE MaDanhMuc = $id";
$result = $conn->query($sql);
if (!$result || $result->num_rows === 0) {
    die("Không tìm thấy danh mục!");
}
$dm = $result->fetch_assoc();

// Nếu nhấn nút cập nhật
if (isset($_POST['submit'])) {
    $ten = trim($conn->real_escape_string($_POST['ten']));
    $mota = trim($conn->real_escape_string($_POST['mota']));

    if ($ten === '') {
        echo "<script>alert('Tên danh mục không được để trống!');</script>";
    } else {
        $sql_update = "UPDATE danhmuc SET TenDanhMuc='$ten', MoTa='$mota' WHERE MaDanhMuc=$id";
        if ($conn->query($sql_update)) {
            echo "<script>
                alert('Cập nhật danh mục thành công!');
                window.location.href =  'admin.php?page=danhsachdanhmuc';
            </script>";
            exit;
        } else {
            echo "<script>alert('Lỗi khi cập nhật: " . addslashes($conn->error) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="suadanhmuc.css">
<title>Sửa danh mục</title>

<h1>Sửa danh mục</h1>

<form method="post">
    <label>Mã danh mục:</label>
    <input type="text" value="<?= htmlspecialchars($dm['MaDanhMuc']) ?>" readonly>

    <label>Tên danh mục:</label>
    <input type="text" name="ten" value="<?= htmlspecialchars($dm['TenDanhMuc']) ?>" required>

    <label>Mô tả:</label>
    <textarea name="mota" rows="3"><?= htmlspecialchars($dm['MoTa']) ?></textarea>

    <button type="submit" name="submit">Cập nhật danh mục</button>
</form>

<div style="text-align:center; margin-top:20px;">
     <a href="javascript:history.back()">← Quay lại danh sách</a>
</div>

</body>
</html>
