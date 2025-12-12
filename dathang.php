<?php
header('Content-Type: application/json; charset=utf-8');

// 1️⃣ Kết nối DB
$conn = new mysqli("localhost", "root", "", "food");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "msg" => "Lỗi kết nối DB"]);
    exit;
}
$conn->set_charset("utf8");

// 2️⃣ Nhận JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["status"=>"error","msg"=>"Không nhận được JSON"]);
    exit;
}

// 3️⃣ Kiểm tra dữ liệu bắt buộc
$required = ["loai_dat_hang","ten","diachi_khach","sdt","latitude","longitude","cuahang","diachi_cua_hang","ngay_dat","gio_dat"];
foreach ($required as $key) {
    if (!isset($data[$key]) || $data[$key] === "") {
        echo json_encode(["status"=>"error","msg"=>"Thiếu dữ liệu: $key"]);
        exit;
    }
}

// 4️⃣ Gán biến
$loai       = $data["loai_dat_hang"];
$ten        = $data["ten"];
$diachi_kh  = $data["diachi_khach"];
$diachi_ch  = $data["diachi_cua_hang"];
$sdt        = $data["sdt"];
$lat        = $data["latitude"];
$lng        = $data["longitude"];
$cuahang    = $data["cuahang"];
$ngay       = $data["ngay_dat"];
$gio        = $data["gio_dat"];
$created    = date("Y-m-d H:i:s");

// 5️⃣ Câu SQL đúng thứ tự
$sql = "INSERT INTO dathang 
        (loai_dat_hang, ten, diachi_khach, diachi, sdt, latitude, longitude, cuahang, ngay_dat, gio_dat, thoi_gian_tao) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status"=>"error","msg"=>"Lỗi prepare: ".$conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssddsssss",
    $loai, $ten, $diachi_kh, $diachi_ch, $sdt, $lat, $lng,
    $cuahang, $ngay, $gio, $created
);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","msg"=>$stmt->error]);
}

$stmt->close();
$conn->close();
?>
