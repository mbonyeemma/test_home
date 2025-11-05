<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #e74c3c;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .otp-box {
            background: #f8f9fa;
            border: 2px dashed #3498db;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border-radius: 8px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #3498db;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            color: #856404;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $userName }}</strong>,</p>
            
            <p>You have requested to reset your password. Please use the OTP code below to verify your identity:</p>
            
            <div class="otp-box">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">Your OTP Code:</p>
                <div class="otp-code">{{ $otp }}</div>
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">Valid for 10 minutes</p>
            </div>
            
            <div class="warning">
                <strong>Security Notice:</strong> Do not share this code with anyone. RESTRACK staff will never ask for your OTP code.
            </div>
            
            <p>If you did not request this password reset, please ignore this email or contact support immediately.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RESTRACK System. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

