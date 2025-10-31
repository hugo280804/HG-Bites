<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HG Bites</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"/>
</head>
<body>
    <header>
        <nav class="navbar flex between">
            <div class="logo">HG Bites Admin</div>
            <div class="user-info flex gap-2">
                <span>Admin</span>
                <a href="#" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            </div>
        </nav>
    </header>

    <main class="dashboard flex">
        <!-- Sidebar -->
    <aside class="sidebar">
    <ul>
        <li><a href="#"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
        <li><a href="danhsachdanhmuc.php"><i class="fa-solid fa-list"></i> Danh mục</a></li>
        <li><a href="danhsachsanpham.php"><i class="fa-solid fa-utensils"></i> Thực đơn</a></li>
         <li><a href="cuahang.php"><i class="fa-solid fa-store"></i> Danh sách cửa hàng</a></li>
        <li><a href="#"><i class="fa-solid fa-receipt"></i> Đơn hàng</a></li>
        <li><a href="#"><i class="fa-solid fa-user"></i> Người dùng</a></li>
        <li><a href="#"><i class="fa-solid fa-user-tie"></i> Nhân Viên</a></li>
        <li><a href="#"><i class="fa-solid fa-chart-line"></i> Thống kê</a></li>
    </ul>
    </aside>
      <div class="danh-muc" id="danhmuc">
      <?php
        if(isset($_GET['page'])){
            $page = $_GET['page'];
            if($page == 'danhsachdanhmuc') include 'danhsachdanhmuc.php';
            else if($page == 'themdanhmuc') include 'themdanhmuc.php';
        } else {
            echo "<h2>Chào mừng bạn đến trang quản trị HG Bites!</h2>";
        }
    ?>  
     
    </div>
    <div class="thuc-don" id="thucdon">
     < <?php
        if(isset($_GET['page'])){
            $page = $_GET['page'];
            if($page == 'danhsachsanpham') include 'danhsachsanpham.php';
            else if($page == 'themsp') include 'themsp.php';
        } else {
            echo "<h2>Chào mừng bạn đến trang quản trị HG Bites!</h2>";
        }
    ?>   
    </div> 
       <div class="cua-hang" id="cuahang">
     < <?php
        if(isset($_GET['page'])){
            $page = $_GET['page'];
            if($page == 'cuahang') include 'cuahang.php';
            else if($page == 'themcuahang') include 'themcuahang.php';
        } else {
            echo "<h2></h2>";
        }
    ?>   
    </div> 


    


    <script src="admin.js"></script>
</body>
</html>
