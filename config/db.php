<?php
mysqli_report(MYSQLI_REPORT_OFF);

$envPath = dirname(__DIR__) . '/.env';

if (!file_exists($envPath)) {
    http_response_code(500);
    exit(json_encode([
        "status" => false,
        "message" => "Server configuration error"
    ]));
}

$env = parse_ini_file($envPath);

$DB_HOST = $env['DB_HOST'] ?? null;
$DB_USER = $env['DB_USER'] ?? null;
$DB_PASS = $env['DB_PASS'] ?? null;
$DB_NAME = $env['DB_NAME'] ?? null;

if (!$DB_HOST || !$DB_USER || !$DB_NAME) {
    http_response_code(500);
    exit(json_encode([
        "status" => false,
        "message" => "Database configuration missing"
    ]));
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_errno) {
    http_response_code(500);
    exit(json_encode([
        "status" => false,
        "message" => "Internal server error"
    ]));
}
