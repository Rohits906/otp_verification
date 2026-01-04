<?php
header("Content-Type: application/json");

require_once("../config/db.php");

// Set timezone to IST
date_default_timezone_set('Asia/Kolkata');

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "Invalid request body"
    ]));
}

if (empty($data['mobile']) || empty($data['otp'])) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "OTP not verified"
    ]));
}

$mobile = trim($data['mobile']);
$otp = trim($data['otp']);

$sql = "
    SELECT id
    FROM otp_requests
    WHERE mobile = ?
      AND otp = ?
      AND expires_at > CONVERT_TZ(NOW(), 'SYSTEM', '+05:30')
    ORDER BY id DESC
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    exit(json_encode([
        "status" => false,
        "message" => "Server error"
    ]));
}

$stmt->bind_param("ss", $mobile, $otp);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    exit(json_encode([
        "status" => false,
        "message" => "OTP not verified"
    ]));
}

$row = $result->fetch_assoc();

/* Delete OTP after successful verification */
$delete = $conn->prepare("DELETE FROM otp_requests WHERE id = ?");
$delete->bind_param("i", $row['id']);
$delete->execute();

http_response_code(200);
exit(json_encode([
    "status" => true,
    "message" => "OTP verified"
]));
?>