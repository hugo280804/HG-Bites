<?php
header('Content-Type: application/json');

// Thông tin merchant Momo (thay bằng thông tin của bạn)
$partnerCode = "YOUR_PARTNER_CODE";
$accessKey = "YOUR_ACCESS_KEY";
$secretKey = "YOUR_SECRET_KEY";

$amount = isset($_GET['amount']) ? $_GET['amount'] : 10000;
$orderId = time();
$requestId = time();
$orderInfo = "Thanh toán đơn hàng";
$returnUrl = "https://yourwebsite.com/return_url.php";
$notifyUrl = "https://yourwebsite.com/notify_url.php";
$extraData = "";

$rawHash = "partnerCode=$partnerCode&accessKey=$accessKey&requestId=$requestId&amount=$amount&orderId=$orderId&orderInfo=$orderInfo&returnUrl=$returnUrl&notifyUrl=$notifyUrl&extraData=$extraData";
$signature = hash_hmac('sha256', $rawHash, $secretKey);

$data = [
    "partnerCode" => $partnerCode,
    "accessKey" => $accessKey,
    "requestId" => $requestId,
    "amount" => $amount,
    "orderId" => $orderId,
    "orderInfo" => $orderInfo,
    "returnUrl" => $returnUrl,
    "notifyUrl" => $notifyUrl,
    "extraData" => $extraData,
    "requestType" => "captureWallet",
    "signature" => $signature
];

$ch = curl_init("https://test-payment.momo.vn/v2/gateway/api/create");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$result = curl_exec($ch);
curl_close($ch);

$response = json_decode($result, true);
echo json_encode(['qrUrl' => $response['qrCodeUrl'] ?? '']);
