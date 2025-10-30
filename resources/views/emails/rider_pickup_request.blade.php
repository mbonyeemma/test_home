<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Pickup Request</title>
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
            background: #f39c12;
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
        .urgent-badge {
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        .content {
            padding: 20px 0;
        }
        .package-details {
            background: #fff3cd;
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .package-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .package-details td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .package-details td:first-child {
            font-weight: bold;
            width: 40%;
            color: #856404;
        }
        .action-box {
            background: #d4edda;
            border: 2px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .action-box h3 {
            color: #155724;
            margin-top: 0;
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
            <h1>🏍️ Sample Pickup Request</h1>
            <div class="urgent-badge">ACTION REQUIRED</div>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $riderName }}</strong>,</p>
            
            <p>You have been requested to pick up a sample package from a facility under your hub.</p>
            
            <div class="package-details">
                <h3 style="margin-top: 0; color: #856404;">📦 Package Details:</h3>
                <table>
                    <tr>
                        <td>Package Barcode:</td>
                        <td><strong style="font-size: 16px;">{{ $packageData['barcode'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Pickup Location:</td>
                        <td>{{ $packageData['facility'] }}</td>
                    </tr>
                    <tr>
                        <td>Hub:</td>
                        <td>{{ $packageData['hub'] }}</td>
                    </tr>
                    <tr>
                        <td>Number of Samples:</td>
                        <td><strong>{{ $packageData['samples'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Test Type:</td>
                        <td>{{ $packageData['test_type'] }}</td>
                    </tr>
                    <tr>
                        <td>Date Prepared:</td>
                        <td>{{ $packageData['date_prepared'] }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="action-box">
                <h3>📱 Next Steps</h3>
                <p style="margin: 10px 0;">
                    1. Open the RESTRACK mobile app<br>
                    2. Navigate to "Pick Sample Package"<br>
                    3. Scan or enter barcode: <strong>{{ $packageData['barcode'] }}</strong><br>
                    4. Pick up the package from {{ $packageData['facility'] }}<br>
                    5. Update status after pickup
                </p>
            </div>
            
            <p>Please pick up this package at your earliest convenience.</p>
            
            <p style="color: #856404; font-weight: bold;">⚠️ Note: Samples are time-sensitive. Please prioritize this pickup.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RESTRACK System. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

