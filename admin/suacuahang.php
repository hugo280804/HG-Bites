<?php
include __DIR__ . '/../includes/db.php';

// Lấy ID cần sửa
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<script>alert('ID không hợp lệ!'); window.location.href='admin.php?page=cuahang';</script>");
}
$id = (int)$_GET['id'];

// Lấy dữ liệu hiện tại
$sql = "SELECT * FROM cuahang WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("<script>alert('Không tìm thấy cửa hàng!'); window.location.href='admin.php?page=cuahang';</script>");
}
$row = $result->fetch_assoc();

// Cập nhật khi submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten = $_POST['ten'];
    $dia_chi = $_POST['diachi'];
    $sdt = $_POST['sdt'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $sql_update = "UPDATE cuahang SET ten=?, diachi=?, sdt=?, latitude=?, longitude=? WHERE id=?";
    $stmt2 = $conn->prepare($sql_update);
    $stmt2->bind_param("sssddi", $ten, $dia_chi, $sdt, $lat, $lng, $id);
    $stmt2->execute();

    echo "<script>
        alert('Cập nhật thành công!');
        window.location.href='admin.php?page=cuahang';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa cửa hàng</title>
  <link rel="stylesheet" href="themcuahang.css">
</head>
<body>

<div class="main-content">
  <h1>✏️ Sửa cửa hàng</h1>

  <form method="POST">
    <label>Tên cửa hàng:</label>
    <input type="text" name="ten" value="<?= htmlspecialchars($row['ten']) ?>" required>

    <label>Địa chỉ:</label>
    <input type="text" name="diachi" value="<?= htmlspecialchars($row['diachi']) ?>" required>

    <label>Số điện thoại:</label>
    <input type="text" name="sdt" value="<?= htmlspecialchars($row['sdt']) ?>">

    <label>Vĩ độ (Latitude):</label>
    <input type="text" name="latitude" value="<?= $row['latitude'] ?>" required>

    <label>Kinh độ (Longitude):</label>
    <input type="text" name="longitude" value="<?= $row['longitude'] ?>" required>

    <button type="submit">Lưu thay đổi</button>
  </form>

  <div class="back-link">
    <a href="javascript:history.back()">← Quay lại danh sách</a>
  </div>
</div>

</body>
</html>
