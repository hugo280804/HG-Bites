<?php
// Xác định page hiện tại
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch($page) {
    // Danh mục
    case 'danhsachdanhmuc':
        include 'danhsachdanhmuc.php';
        break;
    case 'themdanhmuc':
        include 'themdanhmuc.php';
        break;

    // Thực đơn
    case 'danhsachsanpham':
        include 'danhsachsanpham.php';
        break;
    case 'themsp':
        include 'themsp.php';
        break;

    // Mặc định (trang chủ)
    default:
        echo "<h2>Chào mừng bạn đến trang quản trị HG Bites!</h2>";
        break;
}
?>
