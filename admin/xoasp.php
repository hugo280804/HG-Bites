<?php
include __DIR__ . '/../includes/db.php';
if(!isset($_GET['id'])) die("ID sản phẩm không hợp lệ!");
$id = (int)$_GET['id'];

$sql = "DELETE FROM sanpham WHERE id=$id";
$conn->query($sql);
$conn->close();
header("Location: danhsachsanpham.php");
exit;
?>

