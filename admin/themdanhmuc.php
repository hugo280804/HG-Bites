<?php
include __DIR__ . '/../includes/db.php';

if(isset($_POST['submit'])){
    $ma = $conn->real_escape_string($_POST['ma']);
    $ten = $conn->real_escape_string($_POST['ten']);
    $mota = $conn->real_escape_string($_POST['mota']);

    // Kiểm tra trùng mã danh mục
    $check_sql = "SELECT * FROM danhmuc WHERE MaDanhMuc = '$ma'";
    $result = $conn->query($check_sql);

    if($result->num_rows > 0){
        // Nếu trùng
        echo "<script>
            alert('Mã danh mục này đã tồn tại! Vui lòng nhập mã khác.');
            window.history.back(); // Quay lại form
        </script>";
        exit();
    }

    // Nếu không trùng, thêm mới
    $sql = "INSERT INTO danhmuc (MaDanhMuc, TenDanhMuc, MoTa)
            VALUES ('$ma', '$ten', '$mota')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Thêm danh mục thành công!');
            window.location.href = 'admin.php?page=danhsachdanhmuc';
        </script>";
        exit();
    } else {
        echo "<script>alert('Lỗi: " . $conn->error . "');</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="themdanhmuc.css">
<title>Thêm danh mục</title>
</head>
<body>

<div class="main-content">
    <h1>Thêm danh mục mới</h1>

    <form method="post">
        <label>Mã danh mục:</label>
        <input type="text" name="ma" required>

        <label>Tên danh mục:</label>
        <input type="text" name="ten" required>

        <label>Mô tả:</label>
        <textarea name="mota" rows="3"></textarea>

        <button type="submit" name="submit">Thêm danh mục</button>
    </form>

    <div class="back-link">
        <a href="javascript:history.back()">← Quay lại danh sách</a>
    </div>
</div>



</body>

</html>
