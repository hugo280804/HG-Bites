let mode = "giaohang";
const getLocationBtn = document.getElementById("getLocationBtn");
const addressInput = document.getElementById("addressInput");
const tabs = document.querySelectorAll(".tab");
const storeList = document.getElementById("store-list");
const mapContainer = document.getElementById("map");
const timeInput = document.getElementById("timeInput");
const dateInput = document.getElementById("dateInput");
const datetimeDisplay = document.getElementById("datetime-display");

// Container cửa hàng gần nhất
const nearestStoreContainer = document.createElement("div");
nearestStoreContainer.id = "nearest-store-container";
nearestStoreContainer.style.position = "fixed";
nearestStoreContainer.style.top = "50%";
nearestStoreContainer.style.left = "50%";
nearestStoreContainer.style.transform = "translate(-50%, -50%)";
nearestStoreContainer.style.background = "#fff";
nearestStoreContainer.style.padding = "15px";
nearestStoreContainer.style.boxShadow = "0 5px 15px rgba(0,0,0,0.3)";
nearestStoreContainer.style.zIndex = "2000";
nearestStoreContainer.style.display = "none";
nearestStoreContainer.style.maxHeight = "300px";
nearestStoreContainer.style.overflowY = "auto";
document.body.appendChild(nearestStoreContainer);

let map = null, userLat = null, userLng = null, storeData = [], routingControl = null, userMarker = null, nearestStore = null;

// 🔹 Khởi tạo map
function initMap() {
  map = L.map('map').setView([10.7769, 106.7009], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);
}

// 🔹 Lấy danh sách cửa hàng
async function loadStores() {
  try {
    const res = await fetch("cuahang.php");
    storeData = await res.json();
    if (map) renderStores(storeData);
  } catch (err) {
    console.error("Không tải được cửa hàng:", err);
  }
}

// 🔹 Bấm định vị
getLocationBtn.addEventListener("click", () => {
  if (!navigator.geolocation) return alert("Trình duyệt không hỗ trợ định vị!");
  getLocationBtn.textContent = "Đang lấy vị trí...";
  navigator.geolocation.getCurrentPosition(success, error, { enableHighAccuracy: true });
});

function success(pos) {
  userLat = pos.coords.latitude;
  userLng = pos.coords.longitude;
  fetchAddress(userLat, userLng);
  updateDateTime();
  getLocationBtn.textContent = "Sử dụng vị trí hiện tại của tôi";
}

function error() {
  alert("Không thể lấy vị trí!");
  getLocationBtn.textContent = "Sử dụng vị trí hiện tại của tôi";
}

// 🔹 Lấy địa chỉ từ Nominatim
function fetchAddress(lat, lng) {
  fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&accept-language=vi`, {
    headers: { "User-Agent": "MyMapApp/1.0 (youremail@example.com)" }
  })
  .then(res => res.json())
  .then(data => addressInput.value = data.display_name || "")
  .catch(err => { console.error(err); addressInput.value = ""; });
}

// 🔹 Tính khoảng cách
function tinhKhoangCach(lat1, lon1, lat2, lon2) {
  const R = 6371;
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2)**2 +
            Math.cos(lat1*Math.PI/180) *
            Math.cos(lat2*Math.PI/180) *
            Math.sin(dLon/2)**2;
  return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// 🔹 Gợi ý địa chỉ
const suggestionsContainer = document.createElement("div");
suggestionsContainer.classList.add("suggestions");
addressInput.parentNode.appendChild(suggestionsContainer);

const style = document.createElement("style");
style.innerHTML = `
.suggestions {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: #fff;
  z-index: 2000;
  border: 1px solid #ccc;
  max-height: 200px;
  overflow-y: auto;
}
.suggestion-item {
  padding: 5px 10px;
  cursor: pointer;
}
.suggestion-item:hover { background: #f0f0f0; }
`;
document.head.appendChild(style);

addressInput.addEventListener("input", async () => {
  const query = addressInput.value.trim();
  suggestionsContainer.innerHTML = "";
  if (!query) return;

  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1&limit=5&accept-language=vi`, {
      headers: { "User-Agent": "MyMapApp/1.0 (youremail@example.com)" }
    });
    const data = await res.json();

    data.forEach(place => {
      const div = document.createElement("div");
      div.classList.add("suggestion-item");
      div.textContent = place.display_name;
      div.addEventListener("click", () => {
        addressInput.value = place.display_name;
        userLat = parseFloat(place.lat);
        userLng = parseFloat(place.lon);
        suggestionsContainer.innerHTML = "";
        handleMode();
        updateDateTime();
      });
      suggestionsContainer.appendChild(div);
    });
  } catch (err) { console.error(err); }
});

// 🔹 Chọn GIAO / MANG
tabs.forEach(tab => {
  tab.addEventListener("click", () => {
    tabs.forEach(t => t.classList.remove("active"));
    tab.classList.add("active");

    // Lấy chữ hiển thị trên nút
    mode = tab.textContent.trim(); // "GIAO HÀNG" hoặc "MANG VỀ"

    // Nếu chưa có tọa độ, thử lấy từ địa chỉ nhập
    if ((!userLat || !userLng) && addressInput.value.trim()) {
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(addressInput.value.trim())}&limit=1&accept-language=vi`)
        .then(res => res.json())
        .then(data => {
          if (data && data.length > 0) {
            userLat = parseFloat(data[0].lat);
            userLng = parseFloat(data[0].lon);
          }
          proceedMode();
        })
        .catch(err => {
          console.error(err);
          proceedMode(); // vẫn tiếp tục nếu lỗi
        });
    } else {
      proceedMode();
    }

    function proceedMode() {
      mapContainer.style.display = (mode === "MANG VỀ") ? "block" : "none";
      nearestStoreContainer.style.display = "none";

      if (mode === "MANG VỀ" && !map) initMap();

      handleMode();

      if (map && mode === "MANG VỀ") setTimeout(() => map.invalidateSize(), 100);
    }
  });
});


// 🔹 Hiển thị cửa hàng gần nhất
function handleMode() {
  if (!userLat || !userLng || storeData.length === 0) return;

  const selectedDateTime = getSelectedDateTime();
  const dateTimeText = selectedDateTime ? `${selectedDateTime.date} ${selectedDateTime.time}` : "Chưa chọn";
  const customerAddress = addressInput.value || "Chưa có địa chỉ";

  // Tìm cửa hàng gần nhất
  let nearest = null, minDist = Infinity;
  storeData.forEach(s => {
    const d = tinhKhoangCach(userLat, userLng, parseFloat(s.latitude), parseFloat(s.longitude));
    if (d < minDist) { minDist = d; nearest = s; }
  });
  if (!nearest) return;
  nearestStore = nearest;

  // Xóa đường đi cũ nếu có
  if (routingControl && map) { map.removeControl(routingControl); routingControl = null; }

  // Hiển thị map chỉ khi MANG VỀ
  if (mode === "MANG VỀ") {
    if (!map) initMap();
    if (userMarker) map.removeLayer(userMarker);
    userMarker = L.marker([userLat, userLng]).addTo(map).bindPopup("📍 Bạn ở đây").openPopup();

    routingControl = L.Routing.control({
      waypoints: [
        L.latLng(userLat, userLng),
        L.latLng(parseFloat(nearest.latitude), parseFloat(nearest.longitude))
      ],
      lineOptions: { styles: [{ color: "#e4002b", weight: 5 }] },
      createMarker: () => null
    }).addTo(map);

    map.fitBounds(L.latLngBounds([
      [userLat, userLng],
      [parseFloat(nearest.latitude), parseFloat(nearest.longitude)]
    ]));

    mapContainer.style.display = "block";
    setTimeout(() => map.invalidateSize(), 100);
  } else {
    mapContainer.style.display = "none";
  }

  // Popup cửa hàng
  nearestStoreContainer.innerHTML = `
    <span class="close-btn">&times;</span>
    <h3>${nearest.ten}</h3>
    <p><b>Địa chỉ cửa hàng:</b> ${nearest.diachi}</p>
    <p>📞 ${nearest.sdt}</p>
    <p><b>Khoảng cách:</b> ${minDist.toFixed(2)} km</p>
    <p><b>Ngày giờ đặt:</b> ${dateTimeText}</p>
    <p><b>Địa chỉ khách hàng:</b> ${customerAddress}</p>
    <button id="orderNowBtn" style="padding:5px 10px; background:#e4002b; color:#fff; border:none; cursor:pointer;">Đặt món</button>
  `;
  nearestStoreContainer.style.display = "block";

  nearestStoreContainer.querySelector(".close-btn").addEventListener("click", () => {
    nearestStoreContainer.style.display = "none";
  });

  nearestStoreContainer.querySelector("#orderNowBtn").addEventListener("click", async () => {
    if (!selectedDateTime) return alert("Vui lòng chọn ngày giờ trước khi đặt món!");
    const orderData = {
      loai_dat_hang: mode,  // giữ nguyên chữ hoa + dấu
      ten: "Khách hàng",
      diachi_khach: customerAddress,
      sdt: "0123456789",
      latitude: userLat,
      longitude: userLng,
      cuahang: nearestStore.ten,
      diachi_cua_hang: nearestStore.diachi,
      ngay_dat: selectedDateTime.date,
      gio_dat: selectedDateTime.time
    };

    try {
      const res = await fetch("dathang.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(orderData)
      });
      const result = await res.json();
      if (result.status === "success") {
        localStorage.setItem("lastOrder", JSON.stringify(orderData));
        window.location.href = "menu1.html";
      } else {
        alert("Lỗi: " + result.msg);
      }
    } catch (err) {
      console.error(err);
      alert("Không gửi được dữ liệu đặt món!");
    }
  });
}


// 🔹 Hiển thị tất cả cửa hàng trên map
function renderStores(stores) {
  if (!map) return;
  stores.forEach(store => {
    const lat = parseFloat(store.latitude);
    const lon = parseFloat(store.longitude);
    const marker = L.marker([lat, lon]).addTo(map);
    marker.bindPopup(`<b>${store.ten}</b><br>${store.diachi}<br>📞 ${store.sdt}`);
  });
}

// 🔹 Lấy ngày giờ đã chọn
function getSelectedDateTime() {
  const date = dateInput.value;
  const time = timeInput.value;
  if (!date || !time) return null;
  return { date, time };
}

// 🔹 Ngày giờ hiện tại
function updateDateTime() {
  const now = new Date();
  const formatted = now.toLocaleDateString('vi-VN') + " " + now.toLocaleTimeString('vi-VN');
  datetimeDisplay.textContent = "Ngày giờ hiện tại: " + formatted;
}
setInterval(updateDateTime, 1000);
updateDateTime();

// 🔹 Khởi chạy
(async function init() {
  await loadStores();
  mapContainer.style.display = "none";
  nearestStoreContainer.style.display = "none";
})();
function fetchAddress(lat, lng) {
  fetch(`geocode.php?lat=${lat}&lon=${lng}`)
    .then(res => res.json())
    .then(data => addressInput.value = data.display_name || "")
    .catch(err => { console.error(err); addressInput.value = ""; });
}
addressInput.addEventListener("input", async () => {
  const query = addressInput.value.trim();
  suggestionsContainer.innerHTML = "";
  if (!query) return;

  try {
    const res = await fetch(`geocode.php?q=${encodeURIComponent(query)}`);
    const data = await res.json();

    data.forEach(place => {
      const div = document.createElement("div");
      div.classList.add("suggestion-item");
      div.textContent = place.display_name;
      div.addEventListener("click", () => {
        addressInput.value = place.display_name;
        userLat = parseFloat(place.lat);
        userLng = parseFloat(place.lon);
        suggestionsContainer.innerHTML = "";
        handleMode();
        updateDateTime();
      });
      suggestionsContainer.appendChild(div);
    });
  } catch (err) {
    console.error(err);
  }
});
