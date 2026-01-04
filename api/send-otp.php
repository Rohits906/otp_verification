<?php
header("Content-Type: application/json");

require_once("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['mobile'])) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "Mobile number is required"
    ]));
}

$mobile = trim($data['mobile']);

if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "Invalid mobile number"
    ]));
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

$otp = random_int(100000, 999999);

$createdAt = date("Y-m-d H:i:s");
$expiresAt = date("Y-m-d H:i:s", strtotime("+5 minutes"));

$stmt = $conn->prepare(
    "INSERT INTO otp_requests (mobile, otp, expires_at, created_at)
     VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssss", $mobile, $otp, $expiresAt, $createdAt);
$stmt->execute();

http_response_code(200);
exit(json_encode([
    "status" => true,
    "message" => "OTP sent successfully"
]));
?>