<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h4>OTP Verification</h4>
            </div>
            <div class="card-body">
                <div id="sendOtpSection">
                    <h5>Send OTP</h5>
                    <form id="sendOtpForm">
                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile" placeholder="Enter 10-digit mobile number" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send OTP</button>
                    </form>
                </div>
                <div id="verifyOtpSection" style="display: none;">
                    <h5>Verify OTP</h5>
                    <form id="verifyOtpForm">
                        <div class="mb-3">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="otp" placeholder="Enter 6-digit OTP" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                        <button type="button" id="backBtn" class="btn btn-secondary w-100 mt-2">Back</button>
                    </form>
                </div>
                <div id="message" class="alert" style="display: none;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sendOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const mobile = document.getElementById('mobile').value;
            if (!/^[6-9]\d{9}$/.test(mobile)) {
                showMessage('Invalid mobile number. Please enter a valid 10-digit number starting with 6-9.', 'danger');
                return;
            }
            fetch('api/send-otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mobile: mobile })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    showMessage('OTP sent successfully!', 'success');
                    document.getElementById('sendOtpSection').style.display = 'none';
                    document.getElementById('verifyOtpSection').style.display = 'block';
                } else {
                    showMessage(data.message, 'danger');
                }
            })
            .catch(error => {
                showMessage('An error occurred. Please try again.', 'danger');
            });
        });

        document.getElementById('verifyOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const mobile = document.getElementById('mobile').value;
            const otp = document.getElementById('otp').value;
            fetch('api/verify-otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mobile: mobile, otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    showMessage('OTP verified successfully!', 'success');
                    setTimeout(() => {
                        document.getElementById('verifyOtpSection').style.display = 'none';
                        document.getElementById('sendOtpSection').style.display = 'block';
                        document.getElementById('mobile').value = '';
                        document.getElementById('otp').value = '';
                        hideMessage();
                    }, 2000);
                } else {
                    showMessage(data.message, 'danger');
                }
            })
            .catch(error => {
                showMessage('An error occurred. Please try again.', 'danger');
            });
        });

        document.getElementById('backBtn').addEventListener('click', function() {
            document.getElementById('verifyOtpSection').style.display = 'none';
            document.getElementById('sendOtpSection').style.display = 'block';
            document.getElementById('otp').value = '';
            hideMessage();
        });

        function showMessage(message, type) {
            const msgDiv = document.getElementById('message');
            msgDiv.className = `alert alert-${type}`;
            msgDiv.textContent = message;
            msgDiv.style.display = 'block';
        }

        function hideMessage() {
            document.getElementById('message').style.display = 'none';
        }
    </script>
</body>
</html>