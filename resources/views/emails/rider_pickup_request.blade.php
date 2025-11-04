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
            padding: 30px;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        .urgent-note {
            background: #f8f8f8;
            padding: 10px 15px;
            margin: 15px 0;
            border-left: 3px solid #333;
        }
        .content {
            padding: 10px 0;
        }
        .package-details {
            background: #fafafa;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .package-details h3 {
            margin-top: 0;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .package-details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .package-details td {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .package-details td:first-child {
            font-weight: bold;
            width: 45%;
        }
        .package-details tr:last-child td {
            border-bottom: none;
        }
        .instructions {
            background: #f8f8f8;
            padding: 20px;
            margin: 20px 0;
        }
        .instructions h3 {
            margin-top: 0;
            font-size: 16px;
            color: #333;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sample Pickup Request</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $riderName }}</strong>,</p>
            
            <p>You have been requested to pick up a sample package from a facility under your hub.</p>
            
            <div class="urgent-note">
                <strong>Note:</strong> Samples are time-sensitive. Please prioritize this pickup.
            </div>
            
            <div class="package-details">
                <h3>Package Details</h3>
                <table>
                    <tr>
                        <td>Package Barcode:</td>
                        <td><strong>{{ $packageData['barcode'] }}</strong></td>
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
                        <td>{{ $packageData['samples'] }}</td>
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
            
            <div class="instructions">
                <h3>Instructions</h3>
                <ol>
                    <li>Open the RESTRACK mobile app</li>
                    <li>Navigate to "Pick Sample Package"</li>
                    <li>Scan or enter barcode: <strong>{{ $packageData['barcode'] }}</strong></li>
                    <li>Pick up the package from {{ $packageData['facility'] }}</li>
                    <li>Update the status after pickup</li>
                </ol>
            </div>
            
            <p>Please pick up this package at your earliest convenience.</p>
        </div>
        
        <div class="footer">
            <p>RESTRACK System - {{ date('Y') }}</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

