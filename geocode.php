<?php
header('Content-Type: application/json; charset=utf-8');

// Nominatim OpenStreetMap base URL
$base_url = "https://nominatim.openstreetmap.org/";

function fetchFromNominatim($url) {
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: MyMapApp/1.0 (youremail@example.com)\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $result = @file_get_contents($url, false, $context);
    if ($result === FALSE) return [];
    return json_decode($result, true);
}

// Reverse geocode (lat & lon)
if (isset($_GET['lat']) && isset($_GET['lon'])) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $url = $base_url . "reverse?format=json&lat={$lat}&lon={$lon}&accept-language=vi";
    $data = fetchFromNominatim($url);
    echo json_encode($data);
    exit;
}

// Search (query)
if (isset($_GET['q'])) {
    $q = urlencode($_GET['q']);
    $url = $base_url . "search?format=json&q={$q}&addressdetails=1&limit=5&accept-language=vi";
    $data = fetchFromNominatim($url);
    echo json_encode($data);
    exit;
}

// Nếu không có param
echo json_encode([]);
?>
