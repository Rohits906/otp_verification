<?php
mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";      
$DB_NAME = "otp_db";
$DB_PORT = 3306;          // Set this based on your MySQL port

$conn = new mysqli(
    $DB_HOST,
    $DB_USER,
    $DB_PASS,
    $DB_NAME,
    $DB_PORT
);

if ($conn->connect_errno) {
    http_response_code(500);
    exit(json_encode([
        "status" => false,
        "message" => "Database connection failed",
    ]));
}

$conn->set_charset("utf8mb4");
