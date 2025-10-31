document.addEventListener('DOMContentLoaded', () => {
    const danhMucInner = document.getElementById('danhmucInner');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const openBtn = document.getElementById('openSearch');
    const miniPage = document.getElementById('miniPage');
    const closeMini = document.getElementById('closeMini');
    const productsContainer = document.getElementById('products');

    // ===== Load danh mục =====
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
        });
        

    // ===== Nút scroll =====
    const danhMucContainer = document.getElementById('danhmuc-container');


document.getElementById('prev').addEventListener('click', () => {
    danhMucContainer.scrollBy({ left: -135, behavior: 'smooth' });
});

document.getElementById('next').addEventListener('click', () => {
    danhMucContainer.scrollBy({ left: 135, behavior: 'smooth' });
});

    // ===== Popup search =====
    openBtn.addEventListener('click', () => {
        miniPage.style.display = 'flex';
        document.getElementById('searchInput').focus();
    });
    closeMini.addEventListener('click', () => miniPage.style.display = 'none');
    miniPage.addEventListener('click', e => { if(e.target === miniPage) miniPage.style.display='none'; });

    // ===== Load sản phẩm =====
    function loadSanPham(maDanhMuc='all') {
        fetch(`sanpham.php?danhmuc=${maDanhMuc}`)
            .then(res => res.json())
            .then(data => {
                productsContainer.innerHTML = '';
                if(!Array.isArray(data) || !data.length){
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
                        <a href="them.php?id=${p.id}" class="btn-cart">Thêm vào giỏ hàng</a>
                    `;
                    productsContainer.appendChild(div);
                });
            })
            .catch(err => {
                console.error(err);
                productsContainer.innerHTML = '<p>Lỗi khi tải sản phẩm</p>';
            });
    }
    

    // Load tất cả sản phẩm mặc định
    loadSanPham('all');
    
});
document.addEventListener('DOMContentLoaded', () => {
  const miniPage = document.getElementById('miniPage');
  const searchBtn = document.getElementById('searchBtn');
  const closeMini = document.getElementById('closeMini');
  const searchInput = document.getElementById('searchInput');
  const resultContainer = document.getElementById('ketqua');
  const openSearch = document.getElementById('openSearch'); // nút mở popup
  document.getElementById('ketqua').style.display = 'none';

  // ⚙️ Mở popup tìm kiếm
  openSearch.addEventListener('click', () => {
    miniPage.style.display = 'flex';
    searchInput.focus();
  });

  // 🔍 Nút "Tìm"
  searchBtn.addEventListener('click', () => {
    const keyword = searchInput.value.trim();
    if (!keyword) {
      alert('Vui lòng nhập từ khóa!');
      return;
    }
    resultContainer.style.display = 'block';

   if (products) products.innerHTML = '';
    fetch(`sanpham.php?keyword=${encodeURIComponent(keyword)}`)
      .then(res => res.json())
      .then(data => {
        resultContainer.innerHTML = '';

        if (!data || data.length === 0) {
          resultContainer.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
          return;
        }

        // 🧾 Hiển thị danh sách sản phẩm
            resultContainer.style.display = 'grid'; // hoặc 'block' vẫn ok nhưng grid mới ngang
            resultContainer.innerHTML = ''; // xóa các kết quả cũ
            data.forEach(item => {
                const sp = document.createElement('div');
                sp.classList.add('product'); 
                sp.innerHTML = `
                    <img src="img/${item.anh}" alt="${item.ten}">
                    <div class="info">
                        <h3>${item.ten}</h3>
                        <p class="ghi-chu">${item.ghi_chu || item.tendanhmuc || 'Không có ghi chú'}</p>
                        <span class="price">${Number(item.gia).toLocaleString()} đ</span>
                    </div>
                    <a href="them.php?id=${item.id}" class="btn-cart">Thêm vào giỏ hàng</a>
                `;
                resultContainer.appendChild(sp);
            });




        // Ẩn popup sau khi tìm
        miniPage.style.display = 'none';
      })
      .catch(err => {
        console.error('Lỗi khi tìm kiếm:', err);
        resultContainer.innerHTML = '<p style="color:red;">Lỗi khi tải dữ liệu!</p>';
      });
  });

  // ❌ Đóng popup
  closeMini.addEventListener('click', () => {
    miniPage.style.display = 'none';
  });
  // Sự kiện click danh mục

  
});