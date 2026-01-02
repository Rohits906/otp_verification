<?php
header("Content-Type: application/json");

require_once("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['mobile']) || empty($data['otp'])) {
    http_response_code(400);
    exit(json_encode([
        "status" => false,
        "message" => "Mobile number and OTP are required"
    ]));
}

$mobile = trim($data['mobile']);
$otp    = trim($data['otp']);

$stmt = $conn->prepare(
    "SELECT id FROM otp_requests
     WHERE mobile = ? AND otp = ? AND expires_at > NOW()
     ORDER BY id DESC LIMIT 1"
);

$stmt->bind_param("ss", $mobile, $otp);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    exit(json_encode([
        "status" => false,
        "message" => "Invalid or expired OTP"
    ]));
}

$row = $result->fetch_assoc();

$delete = $conn->prepare("DELETE FROM otp_requests WHERE id = ?");
$delete->bind_param("i", $row['id']);
$delete->execute();

http_response_code(200);
exit(json_encode([
    "status" => true,
    "message" => "OTP verified successfully"
]));
