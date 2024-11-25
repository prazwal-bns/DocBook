<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(to right, #2563eb, #14b8a6);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            color: #333333;
        }
        .content p {
            margin: 0 0 16px;
            line-height: 1.5;
        }
        .content ul {
            margin: 0 0 16px;
            padding-left: 20px;
            color: #333333;
        }
        .content ul li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            New Message Received
        </div>
        <!-- Content -->
        <div class="content">
            <p>Dear User</strong>,</p>
            <p>A new message has arrived to you with the following details:</p>
            <ul>
                <li><strong>Name:</strong> {{ $name }}</li>
                <li><strong>Email:</strong> {{ $email }}</li>
                <li><strong>Message:</strong> {{ $contactMessage }}</li>
            </ul>
            <p>Thank you!</p>
        </div>
        <!-- Footer -->
        <div class="footer">
            This is an automated notification from the appointment management system.
        </div>
    </div>
</body>
</html>
