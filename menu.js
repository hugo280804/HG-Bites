document.addEventListener('DOMContentLoaded', () => {
    // ======== Các biến DOM ========
    const danhMucInner = document.getElementById('danhmucInner');
    const danhMucContainer = document.getElementById('danhmuc-container');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const openSearch = document.getElementById('openSearch');
    const miniPage = document.getElementById('miniPage');
    const closeMini = document.getElementById('closeMini');
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    const resultContainer = document.getElementById('ketqua');
    const productsContainer = document.getElementById('products');
    const infoDiv = document.getElementById('order-info');

    // ======== Hiển thị thông tin đơn hàng từ query string ========
    const params = new URLSearchParams(window.location.search);
    const cuahang = params.get("cuahang");
    const diachi = params.get("diachi");
    const ngay = params.get("ngay");
    const gio = params.get("gio");
    const hinhthuc = params.get("hinhthuc");

    if (cuahang && diachi && ngay && gio && hinhthuc) {
        infoDiv.innerHTML = `
            <h2>Thông tin đơn hàng của bạn</h2>
            <p><b>Cửa hàng:</b> ${cuahang}</p>
            <p><b>Địa chỉ:</b> ${diachi}</p>
            <p><b>Ngày giờ đặt:</b> ${ngay} ${gio}</p>
            <p><b>Hình thức:</b> ${hinhthuc === 'giaohang' ? 'Giao hàng' : 'Mang đi'}</p>
        `;
        infoDiv.style.cssText = `
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px 0;
            background: #f9f9f9;
        `;
    }

    // ======== Load danh mục ========
    fetch('danhmuc.php')
        .then(res => res.json())
        .then(data => {
            danhMucInner.innerHTML = '';

            // Nút "Tất cả"
            const tatCa = document.createElement('div');
            tatCa.className = 'danhmuc-item active';
            tatCa.dataset.id = 'all';
            tatCa.innerHTML = `<p>Tất cả</p>`;
            danhMucInner.appendChild(tatCa);

            // Các danh mục khác
            if (Array.isArray(data)) {
                data.forEach(dm => {
                    const div = document.createElement('div');
                    div.className = 'danhmuc-item';
                    div.dataset.id = dm.id;
                    div.innerHTML = `<p>${dm.ten}</p>`;
                    danhMucInner.appendChild(div);
                });
            }

            // Sự kiện click danh mục
            danhMucInner.querySelectorAll('.danhmuc-item').forEach(item => {
                item.addEventListener('click', () => {
                    danhMucInner.querySelectorAll('.danhmuc-item').forEach(i => i.classList.remove('active'));
                    item.classList.add('active');
                    loadSanPham(item.dataset.id);
                });
            });
        })
        .catch(err => console.error('Lỗi load danh mục:', err));

    // ======== Nút scroll danh mục ========
    prevBtn.addEventListener('click', () => {
        danhMucContainer.scrollBy({ left: -135, behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', () => {
        danhMucContainer.scrollBy({ left: 135, behavior: 'smooth' });
    });

    // ======== Popup tìm kiếm ========
    openSearch.addEventListener('click', () => {
        miniPage.style.display = 'flex';
        searchInput.focus();
    });
    closeMini.addEventListener('click', () => miniPage.style.display = 'none');
    miniPage.addEventListener('click', e => { if(e.target === miniPage) miniPage.style.display='none'; });

    searchBtn.addEventListener('click', () => {
        const keyword = searchInput.value.trim();
        if(!keyword) { alert('Vui lòng nhập từ khóa!'); return; }
        resultContainer.style.display = 'block';
        productsContainer.innerHTML = '';

        fetch(`sanpham.php?keyword=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(data => {
                resultContainer.innerHTML = '';
                if(!Array.isArray(data) || data.length === 0) {
                    resultContainer.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
                    return;
                }
                resultContainer.style.display = 'grid';
                data.forEach(item => {
                    const sp = document.createElement('div');
                    sp.className = 'product';
                    sp.innerHTML = `
                        <img src="img/${item.anh}" alt="${item.ten}">
                        <div class="info">
                            <h3>${item.ten}</h3>
                            <p class="ghi-chu">${item.ghi_chu || item.tendanhmuc || 'Không có ghi chú'}</p>
                            <span class="price">${Number(item.gia).toLocaleString()} đ</span>
                        </div>
                        <a href="#" 
                        class="btn-cart"
                        data-id="${item.id}"
                        data-name="${item.ten}"
                        data-price="${item.gia}"
                        data-img="${item.anh}">
                        Thêm vào giỏ hàng
                        </a>
                    `;

                    resultContainer.appendChild(sp);
                });
                miniPage.style.display = 'none';
            })
            .catch(err => {
                console.error('Lỗi khi tìm kiếm:', err);
                resultContainer.innerHTML = '<p style="color:red;">Lỗi khi tải dữ liệu!</p>';
            });
    });

    // ======== Load sản phẩm ========
    function loadSanPham(maDanhMuc='all') {
        fetch(`sanpham.php?danhmuc=${maDanhMuc}`)
            .then(res => res.json())
            .then(data => {
                productsContainer.innerHTML = '';
                if(!Array.isArray(data) || data.length === 0){
                    productsContainer.innerHTML = '<p>Không có sản phẩm nào</p>';
                    return;
                }
                data.forEach(p => {
                    const div = document.createElement('div');
                    div.className = 'product';
                    div.innerHTML = `
                        <img src="img/${p.anh}" alt="${p.ten}">
                        <div class="info">
                            <h3>${p.ten}</h3>
                            <p class="ghi-chu">${p.ghi_chu || 'Không có ghi chú'}</p>
                            <span class="price">${Number(p.gia).toLocaleString()} đ</span>
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
                    productsContainer.appendChild(div);
                });
            })
            .catch(err => {
                console.error('Lỗi khi tải sản phẩm:', err);
                productsContainer.innerHTML = '<p>Lỗi khi tải sản phẩm</p>';
            });
    }

    // Load tất cả sản phẩm mặc định
    loadSanPham('all');


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
   


