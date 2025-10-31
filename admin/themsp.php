<?php
include __DIR__ . '/../includes/db.php';

// Lấy danh mục để hiển thị trong dropdown
$danhmuc_sql = "SELECT * FROM danhmuc ORDER BY TenDanhMuc ASC";
$danhmuc_result = $conn->query($danhmuc_sql);

// Xử lý khi nhấn nút "Thêm sản phẩm"
if (isset($_POST['submit'])) {
    $ma = $conn->real_escape_string($_POST['ma']);
    $ten = $conn->real_escape_string($_POST['ten']);
    $gia = (float)$_POST['gia'];
    $ghi_chu = $conn->real_escape_string($_POST['ghi_chu']);
    $danh_muc = (int)$_POST['danh_muc']; // Lưu ID danh mục

    // --- RÀNG BUỘC KIỂM TRA MÃ ---
    $check_sql = "SELECT * FROM sanpham WHERE ma = '$ma'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo "<script>
            alert('Mã sản phẩm này đã tồn tại! Vui lòng nhập mã khác.');
            window.history.back();
        </script>";
        exit();
    }
    // --- END RÀNG BUỘC ---

    // Xử lý upload ảnh
    $anh = '';
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
        $target_dir = __DIR__ . "/../img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $anh = time() . '_' . basename($_FILES["anh"]["name"]);
        move_uploaded_file($_FILES["anh"]["tmp_name"], $target_dir . $anh);
    }

    // Thêm sản phẩm
    $sql = "INSERT INTO sanpham (ma, ten, anh, gia, ghi_chu, danh_muc)
            VALUES ('$ma', '$ten', '$anh', $gia, '$ghi_chu', $danh_muc)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Thêm sản phẩm thành công!');
            window.location.href = 'admin.php?page=danhsachsanpham';
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
<title>Thêm sản phẩm</title>
<style>
body { font-family: Arial,sans-serif; background:#f7f8fa; margin:0; padding:40px 0; }
h1 { text-align:center; color:#333; }
form { background:#fff; max-width:500px; margin:30px auto; padding:25px 30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
label { font-weight:bold; display:block; margin-top:12px; color:#444; }
input[type="text"], input[type="number"], select { width:100%; padding:10px; margin-top:6px; border:1px solid #ccc; border-radius:6px; transition: all 0.3s ease; }
input[type="text"]:focus, input[type="number"]:focus, select:focus { border-color:#007bff; outline:none; box-shadow:0 0 5px rgba(0,123,255,0.3); }
input[type="file"] { margin-top:8px; }
input[type="submit"] { margin-top:20px; background-color:#007bff; color:#fff; border:none; padding:12px 20px; border-radius:6px; cursor:pointer; transition:background 0.3s ease; }
input[type="submit"]:hover { background-color:#0056b3; }
.back-link { display:block; text-align:center; margin-top:20px; }
</style>
</head>
<body>

<h1>Thêm sản phẩm mới</h1>

<form method="post" enctype="multipart/form-data">
    <label>Mã sản phẩm:</label>
    <input type="text" name="ma" required>

    <label>Tên sản phẩm:</label>
    <input type="text" name="ten" required>

    <label>Ảnh sản phẩm:</label>
    <input type="file" name="anh" required>

    <label>Giá (VNĐ):</label>
    <input type="number" name="gia" required>

    <label>Danh mục:</label>
    <select name="danh_muc" required>
        <option value="">-- Chọn danh mục --</option>
        <?php
        if ($danhmuc_result && $danhmuc_result->num_rows > 0) {
            while ($dm = $danhmuc_result->fetch_assoc()) {
                echo "<option value='" . (int)$dm['MaDanhMuc'] . "'>" . htmlspecialchars($dm['TenDanhMuc']) . "</option>";
            }
        } else {
            echo "<option value=''>Chưa có danh mục</option>";
        }
        ?>
    </select>

    <label>Mô tả:</label>
    <input type="text" name="ghi_chu">

    <input type="submit" name="submit" value="Thêm sản phẩm">
</form>

<div class="back-link">
    <a href="javascript:history.back()">← Quay lại danh sách</a>
</div>

</body>
</html>
