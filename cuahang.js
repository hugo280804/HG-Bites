// Khởi tạo bản đồ mặc định
const map = L.map('map').setView([10.7769, 106.7009], 13); // HCM
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; OpenStreetMap'
}).addTo(map);

let userLat = null;
let userLng = null;
let storeData = [];
let routingControl = null; // dùng để xoá tuyến đường cũ

// 📍 Lấy vị trí người dùng
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(position => {
    userLat = position.coords.latitude;
    userLng = position.coords.longitude;

    L.marker([userLat, userLng], { title: "Vị trí của bạn" })
      .addTo(map)
      .bindPopup("📍 Bạn đang ở đây")
      .openPopup();

    map.setView([userLat, userLng], 13);
    loadStores();
  }, () => {
    console.warn("Không lấy được vị trí người dùng!");
    loadStores();
  });
} else {
  loadStores();
}

// 🧭 Hàm tính khoảng cách giữa 2 tọa độ (Haversine)
function tinhKhoangCach(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos(lat1 * Math.PI / 180) *
    Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c;
}

// 🏪 Load danh sách cửa hàng
function loadStores() {
  fetch('cuahang.php')
    .then(res => res.json())
    .then(stores => {
      storeData = stores;
      renderStores(stores);
    })
    .catch(err => console.error('Lỗi khi lấy dữ liệu cửa hàng:', err));
}

// 🧾 Hàm hiển thị danh sách cửa hàng
function renderStores(stores) {
  const storeList = document.getElementById('store-list');
  storeList.innerHTML = '';

  // Nút sắp xếp
  const sortBtn = document.createElement('button');
  sortBtn.textContent = '🔍 Sắp xếp theo khoảng cách gần nhất';
  sortBtn.className = 'sort-btn';
  sortBtn.addEventListener('click', () => {
    if (userLat && userLng) {
      const sorted = [...storeData].sort((a, b) => {
        const da = tinhKhoangCach(userLat, userLng, parseFloat(a.latitude), parseFloat(a.longitude));
        const db = tinhKhoangCach(userLat, userLng, parseFloat(b.latitude), parseFloat(b.longitude));
        return da - db;
      });
      renderStores(sorted);
    } else {
      alert('⚠️ Bạn cần bật định vị để sắp xếp theo khoảng cách!');
    }
  });
  storeList.appendChild(sortBtn);

  // Hiển thị từng cửa hàng
  stores.forEach(store => {
    if (store.latitude && store.longitude) {
      const lat = parseFloat(store.latitude);
      const lng = parseFloat(store.longitude);

      // 🧭 Tính khoảng cách
      let distanceText = '';
      if (userLat && userLng) {
        const distance = tinhKhoangCach(userLat, userLng, lat, lng);
        distanceText = `<p><b>Khoảng cách:</b> ${distance.toFixed(2)} km</p>`;
      }

      // 🗺️ Marker trên bản đồ
      const marker = L.marker([lat, lng]).addTo(map);
      marker.bindPopup(`
        <b style="color:#ff6600">${store.ten}</b><br>
        ${store.diachi}<br>
        📞 ${store.sdt}<br>
        ${distanceText}
      `);

      // 📋 Thẻ cửa hàng
      const card = document.createElement('div');
      card.className = 'store-card';
      card.innerHTML = `
        <h3>${store.ten}</h3>
        <p><b>Địa chỉ:</b> ${store.diachi}</p>
        <p><b>Điện thoại:</b> ${store.sdt}</p>
        ${distanceText}
        <button class="btn-duongdi">🚗 Chỉ đường</button>
      `;

      // 🚗 Nút chỉ đường
      card.querySelector('.btn-duongdi').addEventListener('click', () => {
        if (userLat && userLng) {
          // Xoá tuyến cũ nếu có
          if (routingControl) map.removeControl(routingControl);

          routingControl = L.Routing.control({
            waypoints: [
              L.latLng(userLat, userLng),
              L.latLng(lat, lng)
            ],
            routeWhileDragging: false,
            showAlternatives: false,
            lineOptions: {
              styles: [{ color: '#ff6600', weight: 5 }]
            },
            createMarker: function () { return null; },
            addWaypoints: false
          }).addTo(map);

          map.setView([lat, lng], 13);
          marker.openPopup();
        } else {
          alert('⚠️ Vui lòng bật định vị để xem đường đi!');
        }
      });

      // 📍 Khi click card zoom đến cửa hàng
      card.addEventListener('click', () => {
        map.setView([lat, lng], 15);
        marker.openPopup();
      });

      storeList.appendChild(card);
    }
  });
}
