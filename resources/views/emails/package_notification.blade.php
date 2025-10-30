<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package Notification</title>
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
            background: #2ecc71;
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
        .package-info {
            background: #f8f9fa;
            border-left: 4px solid #2ecc71;
            padding: 15px;
            margin: 20px 0;
        }
        .package-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .package-info td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .package-info td:first-child {
            font-weight: bold;
            width: 40%;
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
            <h1>Package {{ $notificationType }}</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $userName }}</strong>,</p>
            
            @if($notificationType === 'CREATED')
                <p>A new package has been created in the system.</p>
            @elseif($notificationType === 'DELIVERED')
                <p>A package has been delivered.</p>
            @elseif($notificationType === 'RECEIVED')
                <p>A package has been received.</p>
            @elseif($notificationType === 'INVITATION')
                <p>You have been invited to pick up a package.</p>
            @endif
            
            <div class="package-info">
                <h3 style="margin-top: 0;">Package Details:</h3>
                <table>
                    @if(isset($packageData['barcode']))
                        <tr>
                            <td>Barcode:</td>
                            <td><strong>{{ $packageData['barcode'] }}</strong></td>
                        </tr>
                    @endif
                    @if(isset($packageData['facility']))
                        <tr>
                            <td>Facility:</td>
                            <td>{{ $packageData['facility'] }}</td>
                        </tr>
                    @endif
                    @if(isset($packageData['destination']))
                        <tr>
                            <td>Destination:</td>
                            <td>{{ $packageData['destination'] }}</td>
                        </tr>
                    @endif
                    @if(isset($packageData['samples']))
                        <tr>
                            <td>Number of Samples:</td>
                            <td>{{ $packageData['samples'] }}</td>
                        </tr>
                    @endif
                    @if(isset($packageData['test_type']))
                        <tr>
                            <td>Test Type:</td>
                            <td>{{ $packageData['test_type'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            
            <p>Please log in to the system for more details.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RESTRACK System. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

