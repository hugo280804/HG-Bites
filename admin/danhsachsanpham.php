<?php
include __DIR__ . '/../includes/db.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách sản phẩm</title>
<style>
table { border-collapse: collapse; width: 90%; margin: 20px auto; }
th, td { border: 1px solid #000; padding: 8px; text-align: left; }
img { max-width: 100px; }
a { margin-right: 10px; text-decoration: none; color: #000; }
a:hover { color: #007BFF; }
h1 { text-align: center; }
</style>
</head>
<body>
<h1>Danh sách sản phẩm</h1>
<div style="text-align:center; margin-bottom:20px;">
    <a href="themsp.php">Thêm sản phẩm mới</a>
</div>

<table>
<tr>
  <th>ID</th>
  <th>Mã</th>
  <th>Tên</th>
  <th>Ảnh</th>
  <th>Giá</th>
  <th>Ghi chú</th>
  <th>Hành động</th>
</tr>

<?php
$sql = "SELECT * FROM sanpham ORDER BY id ASC"; // sửa ở đây
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['ma']}</td>";
        echo "<td>{$row['ten']}</td>";
        echo "<td><img src='../img/{$row['anh']}' alt='{$row['ten']}'></td>";
        echo "<td>" . number_format($row['gia'],0,',','.') . " VNĐ</td>";
        echo "<td>{$row['ghi_chu']}</td>";
        echo "<td>
                <a href='suasp.php?id={$row['id']}'>Sửa</a>
                <a href='xoasp.php?id={$row['id']}' onclick=\"return confirm('Bạn có chắc muốn xóa?')\">Xóa</a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center;'>Chưa có sản phẩm nào</td></tr>";
}
$conn->close();
?>
</table>
</body>
</html>
