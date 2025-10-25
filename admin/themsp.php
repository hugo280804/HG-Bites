<?php
include __DIR__ . '/../includes/db.php';

if(isset($_POST['submit'])){
    $ma = $conn->real_escape_string($_POST['ma']);
    $ten = $conn->real_escape_string($_POST['ten']);
    $gia = (int)$_POST['gia'];
    $ghi_chu = $conn->real_escape_string($_POST['ghi_chu']);

    // Xử lý upload ảnh
    $anh = '';
    if(isset($_FILES['anh']) && $_FILES['anh']['error']==0){
        $target_dir = "../img/";
        $anh = basename($_FILES["anh"]["name"]);
        move_uploaded_file($_FILES["anh"]["tmp_name"], $target_dir . $anh);
    }

    $sql = "INSERT INTO sanpham (ma, ten, anh, gia, ghi_chu) VALUES ('$ma','$ten','$anh',$gia,'$ghi_chu')";
    if($conn->query($sql)){
        header("Location: danhsachsanpham.php");
        exit;
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm sản phẩm</title>
</head>
<body>
<h1>Thêm sản phẩm mới</h1>
<form method="post" enctype="multipart/form-data">
    Mã: <input type="text" name="ma" required><br><br>
    Tên: <input type="text" name="ten" required><br><br>
    Ảnh: <input type="file" name="anh" required><br><br>
    Giá: <input type="number" name="gia" required><br><br>
    Ghi chú: <input type="text" name="ghi_chu"><br><br>
    <input type="submit" name="submit" value="Thêm sản phẩm">
</form>
<a href="danhsachsanpham.php">Quay lại danh sách</a>
</body>
</html>
