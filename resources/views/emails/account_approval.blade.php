<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Status</title>
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
            background: {{ $approved ? '#2ecc71' : '#e74c3c' }};
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
        .status-box {
            background: #f8f9fa;
            border-left: 4px solid {{ $approved ? '#2ecc71' : '#e74c3c' }};
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
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
            <h1>Account Registration Status</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $userName }}</strong>,</p>
            
            <div class="status-box">
                <div class="status-icon">{{ $approved ? '✓' : 'ℹ' }}</div>
                @if($approved)
                    <h2 style="color: #2ecc71; margin: 10px 0;">Account Approved!</h2>
                    <p>Your account has been approved and is now active. You can now log in to the RESTRACK system using your credentials.</p>
                @else
                    <h2 style="color: #e74c3c; margin: 10px 0;">Account Registration Update</h2>
                    <p>Your account registration requires additional review.</p>
                    @if($reason)
                        <p><strong>Reason:</strong> {{ $reason }}</p>
                    @endif
                @endif
            </div>
            
            @if($approved)
                <p>You can access the system at: <strong>{{ config('app.url') }}</strong></p>
            @else
                <p>If you have any questions, please contact the system administrator.</p>
            @endif
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RESTRACK System. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

