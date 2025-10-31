<?php
include __DIR__ . '/../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ten = $_POST['ten'];
    $dia_chi = $_POST['diachi'];
    $sdt = $_POST['sdt'];
    $lat = $_POST['latitude'];
    $lng = $_POST['longitude'];

    $sql = "INSERT INTO cuahang (ten, diachi, sdt, latitude, longitude)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdd", $ten, $dia_chi, $sdt, $lat, $lng);
    $stmt->execute();

    echo "<script>
        alert('Thêm cửa hàng thành công!');
        window.location.href = 'admin.php?page=cuahang';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm cửa hàng</title>
    <link rel="stylesheet" href="themcuahang.css">
</head>
<body>

<div class="main-content">
    <h1>➕ Thêm cửa hàng mới</h1>

    <form method="POST">
        <label>Tên cửa hàng:</label>
        <input type="text" name="ten" placeholder="Nhập tên cửa hàng" required>

        <label>Địa chỉ:</label>
        <input type="text" name="diachi" placeholder="Nhập địa chỉ" required>

        <label>Số điện thoại:</label>
        <input type="text" name="sdt" placeholder="Nhập số điện thoại">

        <label>Vĩ độ (Latitude):</label>
        <input type="text" name="latitude" placeholder="VD: 10.762622" required>

        <label>Kinh độ (Longitude):</label>
        <input type="text" name="longitude" placeholder="VD: 106.660172" required>

        <button type="submit">Thêm cửa hàng</button>
    </form>

    <div class="back-link">
        <a href="javascript:history.back()">← Quay lại danh sách</a>
    </div>
</div>

</body>
</html>
