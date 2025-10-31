document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('.sidebar a');
  const danhmuc = document.getElementById('danhmuc');
  const thucdon = document.getElementById('thucdon');
  const cuahang = document.getElementById('cuahang');

  links.forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const url = link.getAttribute('href');

      // Nếu click vào Trang chủ
      if (url === "#" || url === "") {
        danhmuc.innerHTML = `
          <h1>Trang chủ Admin</h1>
          <p>Chào mừng đến trang quản trị <b>HG Bites</b>!</p>
        `;
        thucdon.innerHTML = "";
        cuahang.innerHTML = "";
        return;
      }

      // Gọi fetch lấy nội dung của file PHP
      fetch(url)
        .then(res => {
          if (!res.ok) throw new Error(`Không thể tải: ${url}`);
          return res.text();
        })
        .then(html => {
          // Nếu là trang sản phẩm/thực đơn
          if (url.includes('sanpham') || url.includes('thucdon')) {
            thucdon.innerHTML = html;
            danhmuc.innerHTML = "";
            cuahang.innerHTML = "";
          }
          // Nếu là trang cửa hàng
          else if (url.includes('cuahang')) {
            cuahang.innerHTML = html;
            danhmuc.innerHTML = "";
            thucdon.innerHTML = "";
          }
          // Còn lại là trang danh mục
          else {
            danhmuc.innerHTML = html;
            thucdon.innerHTML = "";
            cuahang.innerHTML = "";
          }
        })
        .catch(err => {
          console.error("Lỗi khi tải trang:", err);
          danhmuc.innerHTML = `<p style="color:red;">Không thể tải nội dung (${url})</p>`;
        });
    });
  });
});
