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
