document.addEventListener("DOMContentLoaded", () => {
    // --- Menu mobile ---
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        if (mobileMenu.classList.contains('active')) {
            document.body.classList.add('no-scroll');
            menuToggle.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        } else {
            document.body.classList.remove('no-scroll');
            menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
        }
    });

    // --- Hero slider ---
    const imgContainer = document.querySelector('.img-container');
    const images = document.querySelectorAll('.fullscreen-img');
    let index = 0;
    setInterval(() => {
        index = (index + 1) % images.length;
        imgContainer.style.transform = `translateX(-${index * 100}vw)`;
    }, 4000);

    // --- Fetch sản phẩm ---
    const container = document.getElementById('products');
    if (container) {
        fetch('sanpham.php')
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    container.innerHTML = '<p>Chưa có sản phẩm nào</p>';
                    return;
                }
                const latest10 = data.slice(-10).reverse();
                container.innerHTML = '';
                latest10.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'product';
                    const ghiChu = p.ghi_chu?.trim() || 'Không có ghi chú';
                    div.innerHTML = `
                        <img src="img/${p.anh}" alt="${p.ten}">
                        <div class="info">
                            <h3>${p.ten}</h3>
                            <p class="ghi-chu">${ghiChu}</p>
                            <span class="price">${Number(p.gia).toLocaleString()}đ</span>
                        </div>
                        <a href="#" 
                           class="btn-cart" 
                           data-id="${p.id}" 
                           data-name="${p.ten}" 
                           data-price="${p.gia}" 
                           data-img="${p.anh}">
                           Thêm vào giỏ hàng
                        </a>
                    `;
                    container.appendChild(div);
                });
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<p>Lỗi khi tải sản phẩm</p>';
            });
    }

 
    // --- Giỏ hàng ---
const cartIcon = document.querySelector('.cart-icon');
const cartTab = document.querySelector('.cart-tab');
const closeBtn = document.querySelector('.close-btn');
const cartList = document.querySelector('.cart-list');
const cartValue = document.querySelector('.cart-value');
const cartTotal = document.querySelector('.cart-total');

let cart = JSON.parse(localStorage.getItem("cart")) || [];

// --- Cập nhật giao diện giỏ hàng ---
function renderCart() {

    // Cập nhật số lượng cho mọi trang
    let totalQty = cart.reduce((sum, item) => sum + item.qty, 0);

    if (cartValue) cartValue.textContent = totalQty;

    // Nếu trang ko có cartList thì không render popup
    if (!cartList) return;

    cartList.innerHTML = '';
    let total = 0;

    cart.forEach((item, index) => {
        total += item.price * item.qty;

        const div = document.createElement('div');
        div.classList.add('cart-item');
        div.innerHTML = `
            <img src="img/${item.img}" width="60">
            <div class="info">
                <h4>${item.name}</h4>
                <p>${item.price.toLocaleString()}đ</p>
                <div class="qty">
                    <button class="decrease" data-index="${index}">-</button>
                    <span>${item.qty}</span>
                    <button class="increase" data-index="${index}">+</button>
                </div>
            </div>
        `;
        cartList.appendChild(div);
    });

    if (cartTotal) cartTotal.textContent = total.toLocaleString() + "₫";

    localStorage.setItem("cart", JSON.stringify(cart));
}

// --- Mở & đóng giỏ hàng ---
if (cartIcon && cartTab) {
    cartIcon.addEventListener('click', (e) => {
        e.preventDefault();
        cartTab.classList.add('open');
        renderCart();
    });
}

if (closeBtn && cartTab) {
    closeBtn.addEventListener('click', (e) => {
        e.preventDefault();
        cartTab.classList.remove('open');
    });
}

// --- Thêm sản phẩm ---
document.body.addEventListener('click', (e) => {

    if (e.target.classList.contains('btn-cart')) {
        e.preventDefault();

        const btn = e.target;
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const price = parseInt(btn.dataset.price);
        const img = btn.dataset.img;

        const existing = cart.find(item => item.id === id);
        if (existing) existing.qty++;
        else cart.push({ id, name, price, img, qty: 1 });

        renderCart();
    }

    // Nếu trang không có tăng giảm thì bỏ qua
    if (!cartList) return;

    // --- Tăng giảm số lượng ---
    if (e.target.classList.contains('increase')) {
        const index = e.target.dataset.index;
        cart[index].qty++;
        renderCart();
    }

    if (e.target.classList.contains('decrease')) {
        const index = e.target.dataset.index;
        if (cart[index].qty > 1) cart[index].qty--;
        else cart.splice(index, 1);
        renderCart();
    }
});

// --- Đồng bộ giữa các tab ---
window.addEventListener("storage", () => {
    cart = JSON.parse(localStorage.getItem("cart")) || [];
    renderCart();
});

// --- Render khi tải trang ---
renderCart();




});
