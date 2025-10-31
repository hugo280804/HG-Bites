<?php
include __DIR__ . '/../includes/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách danh mục món ăn - Admin</title>
<link rel="stylesheet" href="danhsachdanhmuc.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
</head>
<body>

<div class="main-content">
    <div class="header-row">
        <h1>Danh mục món ăn</h1>
        <a href="themdanhmuc.php" class="add-btn">
            <i class="fa-solid fa-plus"></i> Thêm danh mục mới
        </a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Mã danh mục</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM DanhMuc ORDER BY MaDanhMuc ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>{$row['MaDanhMuc']}</td>";
                    echo "<td>{$row['TenDanhMuc']}</td>";
                    echo "<td>{$row['MoTa']}</td>";
                    echo "<td class='action'>
                            <a href='suadanhmuc.php?id={$row['MaDanhMuc']}' class='edit'>
                                <i class='fa-solid fa-pen-to-square'></i> Sửa
                            </a>
                            <a href='xoadanhmuc.php?id={$row['MaDanhMuc']}' class='delete' onclick=\"return confirm('Bạn có chắc muốn xóa danh mục này?')\">
                                <i class='fa-solid fa-trash'></i> Xóa
                            </a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' align='center'>Chưa có danh mục nào</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
