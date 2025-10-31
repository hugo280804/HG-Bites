<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt charset
$conn->set_charset("utf8mb4");
?>
