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
    if (!container) return;

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
                        <span class="price">${Number(p.gia).toLocaleString()} đ</span>
                    </div>
                    <a href="them.php?id=${p.id}" class="btn-cart">Thêm vào giỏ hàng</a>
                    `;
                    container.appendChild(div);

            });
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p>Lỗi khi tải sản phẩm</p>';
        });
});
