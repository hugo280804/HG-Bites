<?php
// Lấy tổng tiền từ query string
$total = isset($_GET['total']) ? $_GET['total'] : 10000; // ví dụ 10.000₫
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thanh toán Momo QR</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" />
<style>
body { font-family: "Roboto Condensed", sans-serif; margin:0; padding:0; background:#fdf6f0;}
header { background:#f57c00; color:white; padding:1rem 2rem; text-align:center; font-size:1.5rem; font-weight:bold;}
main { max-width: 900px; margin: 2rem auto; background:#fff; padding:2rem; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { color:#f57c00; margin-bottom:1rem;}
.order-info p { font-size:1.1rem; margin:0.4rem 0;}
.cart-list { margin-top:1.5rem; display:flex; flex-direction:column; gap:12px;}
.cart-item { display:flex; justify-content:space-between; align-items:center; background:#ffecdb; padding:10px 15px; border-radius:8px;}
.cart-item img { width:60px; height:60px; object-fit:cover; border-radius:6px;}
.cart-item-info { flex:1; margin-left:15px;}
.cart-item-info h4 { margin:0; color:#f57c00;}
.cart-item-info p { margin:0; font-weight:bold; color:red;}
.checkout-btn { display:block; margin-top:2rem; width:100%; padding:12px; background:#ff8c00; color:white; font-size:1.2rem; font-weight:bold; border:none; border-radius:10px; cursor:pointer; transition:0.2s;}
.checkout-btn:hover { transform:translateY(-2px); box-shadow:0 6px 14px rgba(255,140,0,0.3);}

/* Momo QR fullscreen */
.momo-overlay {
    display:none;
    position:fixed;
    top:0; left:0; width:100%; height:100%;
    background: rgba(0,0,0,0.6);
    justify-content:center; align-items:center;
    z-index:1000;
}
.momo-box {
    background:#fff;
    padding:20px;
    border-radius:12px;
    text-align:center;
    max-width:90%;
}
.momo-box img { width:300px; max-width:80%; height:auto; border-radius:12px; margin-top:10px;}
.momo-box p { font-size:1.2rem; font-weight:bold; color:#f57c00;}
.momo-box button { margin-top:20px; padding:10px 20px; background:#f57c00; color:#fff; border:none; border-radius:8px; cursor:pointer; font-size:1rem;}
</style>
</head>
<body>
<header>Thanh toán đơn hàng</header>

<main>
    <section class="order-info" id="order-info">
        <h2>Thông tin đơn hàng</h2>
        <p><b>Tổng tiền:</b> <?php echo number_format($total); ?>₫</p>
    </section>

    <section class="cart-list" id="cart-list">
        <!-- Giỏ hàng render từ localStorage -->
    </section>

    <button class="checkout-btn" id="checkoutBtn">Thanh toán bằng Momo QR</button>
</main>

<!-- Overlay Momo QR -->
<div class="momo-overlay" id="momoOverlay">
    <div class="momo-box">
        <p>Quét QR Momo để thanh toán</p>
        <img id="qrImage" src="" alt="Momo QR">
        <button id="closeOverlay">Đóng</button>
    </div>
</div>

<script>
// Lấy giỏ hàng từ localStorage
const cart = JSON.parse(localStorage.getItem("cart")) || [];
const cartList = document.getElementById("cart-list");
let total = <?php echo $total; ?>;
cart.forEach(item => {
    const div = document.createElement("div");
    div.className = "cart-item";
    div.innerHTML = `
        <img src="img/${item.img}" alt="${item.name}">
        <div class="cart-item-info">
            <h4>${item.name}</h4>
            <p>${item.price.toLocaleString()}₫ x ${item.qty}</p>
        </div>
        <div class="cart-item-total">${(item.price*item.qty).toLocaleString()}₫</div>
    `;
    cartList.appendChild(div);
});

// Hiển thị QR fullscreen
const checkoutBtn = document.getElementById("checkoutBtn");
const momoOverlay = document.getElementById("momoOverlay");
const qrImage = document.getElementById("qrImage");
const closeOverlay = document.getElementById("closeOverlay");

checkoutBtn.addEventListener("click", () => {
    momoOverlay.style.display = "flex";
    // Gọi PHP tạo QR Momo
    fetch(`create_momo_qr.php?amount=${total}`)
        .then(res => res.json())
        .then(data => {
            if(data.qrUrl) qrImage.src = data.qrUrl;
        });
});

// Đóng overlay
closeOverlay.addEventListener("click", () => {
    momoOverlay.style.display = "none";
});
</script>
</body>
</html>
