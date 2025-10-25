document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById('menuToggle');
  const mobileMenu = document.getElementById('mobileMenu');

  menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('active'); // bật/tắt menu
    menuToggle.classList.toggle('open');   // đổi icon

    // ⚡ Thêm đoạn này để khóa cuộn khi menu mở
    if (mobileMenu.classList.contains('active')) {
      document.body.classList.add('no-scroll');
      menuToggle.innerHTML = '<i class="fa-solid fa-xmark"></i>';
    } else {
      document.body.classList.remove('no-scroll');
      menuToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
    }
  });
});
document.addEventListener("DOMContentLoaded", () => {
  const imgContainer = document.querySelector('.img-container');
  const images = document.querySelectorAll('.fullscreen-img');
  let index = 0;

  setInterval(() => {
    index = (index + 1) % images.length;
    imgContainer.style.transform = `translateX(-${index * 100}vw)`;
  }, 4000); // 4 giây đổi ảnh
});
fetch('get_products.php')
.then(res => res.json())
.then(data => {
    const container = document.getElementById('products');
    if(data.length === 0){
        container.innerHTML = '<p>Chưa có sản phẩm nào</p>';
        return;
    }
    container.innerHTML = '';
    data.forEach(p => {
        const div = document.createElement('div');
        div.className = 'product';
        div.innerHTML = `
            <img src="img/${p.anh}" alt="${p.ten}">
            <h3>${p.ten}</h3>
            <p>Giá: ${Number(p.gia).toLocaleString()} VNĐ</p>
            <a href="chi-tiet.php?id=${p.id}">Xem chi tiết</a>
        `;
        container.appendChild(div);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('products');
    if (!container) return;

    fetch('sanpham.php') // nếu HTML khác thư mục, thay đường dẫn cho đúng
        .then(res => res.json())
        .then(data => {
            if (!data.length) {
                container.innerHTML = '<p>Chưa có sản phẩm nào</p>';
                return;
            }

            container.innerHTML = '';

            data.forEach(p => {
                const div = document.createElement('div');
                div.className = 'product';

                // Ghi chú an toàn
                const ghiChu = p.ghi_chu?.trim() || 'Không có ghi chú';

                div.innerHTML = `
                    <h3>${p.ten}</h3>
                    <img src="img/${p.anh}" alt="${p.ten}">
                    <p>Giá: ${Number(p.gia).toLocaleString()} VNĐ</p>
                    <p class="ghi-chu"> ${ghiChu}</p>
                    <a href="chi-tiet.php?id=${p.id}">Xem chi tiết</a>
                `;

                container.appendChild(div);
            });
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p>Lỗi khi tải sản phẩm</p>';
        });
});


