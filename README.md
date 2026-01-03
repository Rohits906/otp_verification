# 📱 PHP OTP Verification API (Localhost)

A simple OTP (One-Time Password) verification system built using **PHP & MySQL**, tested on **XAMPP (localhost)**.  
This API allows you to **send OTP**, **store it securely**, and **verify OTP with expiry handling**.

---

## 🚀 Features

- Generate 6-digit OTP
- OTP expires in 5 minutes
- Secure database storage
- OTP verification with expiry check
- OTP auto-delete after verification
- JSON-based REST API
- Works on localhost (XAMPP / WAMP)

---

## 🛠️ Tech Stack

- PHP (MySQLi)
- MySQL
- XAMPP
- Postman (API testing)

---

## 📁 Project Structure

otp-api/
│
├── api/
│   ├── send-otp.php        # Generate & store OTP
│   └── verify-otp.php      # Verify OTP
│
├── config/
│   └── db.php              # Database connection
│
├── README.md

---

## ⚙️ Prerequisites

- XAMPP installed
- Apache & MySQL running
- PHP 8+ recommended
- Postman installed (or any API client)

---

## 🧑‍💻 Step-by-Step Setup (Localhost)

### 1️⃣ Start XAMPP
- Open **XAMPP Control Panel**
- Start **Apache**
- Start **MySQL**

---

### 2️⃣ Create Database

Open browser:
http://localhost/phpmyadmin

sql
Copy code

Run the following SQL:

```sql
CREATE DATABASE otp_api;
USE otp_api;

CREATE TABLE otp_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mobile VARCHAR(15) NOT NULL,
    otp VARCHAR(6) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    INDEX (mobile)
);

```

---

3️⃣ Place Project in XAMPP
Copy the project folder into:

makefile
Copy code
C:\xampp\htdocs\otp-api
4️⃣ Database Configuration
File: config/db.php

php
```sql
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$DB_HOST = "127.0.0.1";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "otp_api";
$DB_PORT = 3306;

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
        "message" => "Database connection failed"
    ]));
}

$conn->set_charset("utf8mb4");

```

------

🧪 API Testing Using Postman
🔹 Send OTP

Endpoint
POST http://localhost/otp-api/api/send-otp.php

Headers
Content-Type: application/json

Body (raw → JSON)
{
  "mobile": "9876543210"
}

Response
{
  "status": true,
  "message": "OTP sent successfully"
}

-----
🔹 Verify OTP

POST http://localhost/otp-api/api/verify-otp.php
Headers
Content-Type: application/json

Body
{
  "mobile": "9876543210",
  "otp": "123456"
}

Response
{
  "status": true,
  "message": "OTP verified successfully"
}

----
⏱ OTP Logic
OTP validity: 5 minutes

Expired OTP cannot be verified

OTP is deleted after successful verification

Latest OTP is always used

❗ Common Errors & Fixes
❌ MySQL connection failed
Ensure MySQL is running

Check port (3306 or 3307)

Use 127.0.0.1 instead of localhost

❌ Mobile number is required
Send JSON body correctly

Use raw → JSON in Postman

❌ Invalid or expired OTP
OTP expired (waited more than 5 minutes)

Wrong OTP entered

🔐 Security Notes
Prepared statements (SQL injection safe)

OTP expiry enforced

OTP cannot be reused

JSON-only API responses

👨‍💻 Author
Rohit Soni
PHP / Full-Stack Developer
