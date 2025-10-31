v<?php
include __DIR__ . '/../includes/db.php';

// Lấy ID sản phẩm cần sửa
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy dữ liệu sản phẩm hiện tại
$sql_sp = "SELECT * FROM sanpham WHERE id = $id";
$result_sp = $conn->query($sql_sp);

if (!$result_sp || $result_sp->num_rows == 0) {
    die("Không tìm thấy sản phẩm!");
}

$sp = $result_sp->fetch_assoc();

// Lấy danh sách danh mục (ID + tên)
$sql_dm = "SELECT MaDanhMuc, TenDanhMuc FROM danhmuc ORDER BY TenDanhMuc ASC";
$result_dm = $conn->query($sql_dm);

// Xử lý khi submit form
if (isset($_POST['submit'])) {
    $ma = $conn->real_escape_string($_POST['ma']);
    $ten = $conn->real_escape_string($_POST['ten']);
    $gia = (float)$_POST['gia'];
    $ghi_chu = $conn->real_escape_string($_POST['ghi_chu']);
    $danh_muc_id = (int)$_POST['danh_muc']; // Lưu ID danh mục

    // Xử lý ảnh
    $anh = $sp['anh']; // giữ ảnh cũ
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
        $target_dir = __DIR__ . "/../img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $anh = time() . '_' . basename($_FILES["anh"]["name"]);
        move_uploaded_file($_FILES["anh"]["tmp_name"], $target_dir . $anh);
    }

    // Update sản phẩm
    $sql_update = "
        UPDATE sanpham 
        SET ma='$ma', ten='$ten', gia=$gia, anh='$anh', danh_muc=$danh_muc_id, ghi_chu='$ghi_chu'
        WHERE id=$id
    ";

    if ($conn->query($sql_update)) {
        echo "<script>
            alert('Cập nhật sản phẩm thành công!');
            window.location.href='admin.php?page=danhsachsanpham';
        </script>";
        exit;
    } else {
        echo "<script>alert('Lỗi: ".addslashes($conn->error)."');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa sản phẩm</title>
<style>
body { font-family: Arial,sans-serif; background:#f7f8fa; margin:0; padding:40px 0; }
h1 { text-align:center; color:#333; }
form { background:#fff; max-width:500px; margin:30px auto; padding:25px 30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
label { font-weight:bold; display:block; margin-top:12px; color:#444; }
input[type="text"], input[type="number"], select { width:100%; padding:10px; margin-top:6px; border:1px solid #ccc; border-radius:6px; transition: all 0.3s ease; }
input[type="text"]:focus, input[type="number"]:focus, select:focus { border-color:#007bff; outline:none; box-shadow:0 0 5px rgba(0,123,255,0.3); }
input[type="file"] { margin-top:8px; }
img { max-width:150px; margin-top:10px; border-radius:8px; display:block; }
input[type="submit"] { margin-top:20px; background-color:#007bff; color:#fff; border:none; padding:12px 20px; border-radius:6px; cursor:pointer; transition:background 0.3s ease; }
input[type="submit"]:hover { background-color:#0056b3; }
.back-link { display:block; text-align:center; margin-top:20px; }
</style>
</head>
<body>

<h1>Sửa sản phẩm</h1>

<form method="post" enctype="multipart/form-data">
    <label>Mã sản phẩm:</label>
    <input type="text" name="ma" value="<?= htmlspecialchars($sp['ma']) ?>" required>

    <label>Tên sản phẩm:</label>
    <input type="text" name="ten" value="<?= htmlspecialchars($sp['ten']) ?>" required>

    <label>Ảnh hiện tại:</label>
    <?php if (!empty($sp['anh'])): ?>
        <img src="../img/<?= htmlspecialchars($sp['anh']) ?>" alt="Ảnh sản phẩm">
    <?php endif; ?>
    <input type="file" name="anh">

    <label>Giá (VNĐ):</label>
    <input type="number" step="0.01" name="gia" value="<?= htmlspecialchars($sp['gia']) ?>" required>

    <label>Danh mục:</label>
    <select name="danh_muc" required>
        <option value="">-- Chọn danh mục --</option>
        <?php
        if ($result_dm && $result_dm->num_rows > 0) {
            mysqli_data_seek($result_dm, 0);
            while ($dm = $result_dm->fetch_assoc()) {
                $selected = ($dm['MaDanhMuc'] == $sp['danh_muc']) ? 'selected' : '';
                echo "<option value='{$dm['MaDanhMuc']}' $selected>".htmlspecialchars($dm['TenDanhMuc'])."</option>";
            }
        }
        ?>
    </select>

    <label>Ghi chú:</label>
    <input type="text" name="ghi_chu" value="<?= htmlspecialchars($sp['ghi_chu']) ?>">

    <input type="submit" name="submit" value="Cập nhật sản phẩm">
</form>

<div class="back-link">
    <a href="javascript:history.back()">← Quay lại danh sách</a>
</div>

</body>
</html>
