<?php
include __DIR__ . '/../includes/db.php';

// Lấy danh sách cửa hàng
$sql = "SELECT * FROM cuahang ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách cửa hàng</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: "Roboto", sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 0;
}
.main-content {
    margin: 20px;
    padding: 30px 40px;
    background: #f4f6f8;
    min-height: 100vh;
    box-sizing: border-box;
}
.header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
}
h1 {
    color: #007bff;
    margin: 0;
    font-size: 26px;
}
.add-btn {
    background: #007bff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.add-btn:hover {
    background: #0056b3;
}
.table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: 1px solid #e0e6f0;
}
.table th, .table td {
    padding: 14px 16px;
    border: 1px solid #e0e6f0;
    text-align: center;
    vertical-align: middle;
}
.table th {
    background: #007bff;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.table tr:nth-child(even) {
    background: #f8fbff;
}
.table tr:hover {
    background: #eef5ff;
    transition: background 0.2s ease;
}
.action a {
    display: inline-block;
    margin: 0 6px;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}
.edit {
    background: #28a745;
    color: white;
}
.edit:hover {
    background: #218838;
}
.delete {
    background: #dc3545;
    color: white;
}
.delete:hover {
    background: #b02a37;
}
@media (max-width: 992px) {
    .main-content {
        margin: 10px;
        padding: 15px;
    }
    .table th, .table td {
        padding: 10px;
        font-size: 14px;
    }
}
</style>
</head>
<body>

<div class="main-content">
    <div class="header-row">
        <h1>📍 Danh sách cửa hàng</h1>
        <a href="themcuahang.php" class="add-btn">+ Thêm cửa hàng mới</a>
    </div>

    <table class="table">
        <tr>
            <th>ID</th>
            <th>Tên cửa hàng</th>
            <th>Địa chỉ</th>
            <th>SĐT</th>
            <th>Vĩ độ</th>
            <th>Kinh độ</th>
            <th>Hành động</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>" . htmlspecialchars($row['ten']) . "</td>";
                echo "<td>" . htmlspecialchars($row['diachi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['sdt']) . "</td>";
                echo "<td>{$row['latitude']}</td>";
                echo "<td>{$row['longitude']}</td>";
                echo "<td class='action'>
                        <a href='suacuahang.php?id={$row['id']}' class='edit'>Sửa</a>
                        <a href='xoacuahang.php?id={$row['id']}' class='delete' onclick=\"return confirm('Bạn có chắc muốn xóa cửa hàng này?')\">Xóa</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Chưa có cửa hàng nào</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

</body>
</html>
