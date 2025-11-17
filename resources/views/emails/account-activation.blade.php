<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kích Hoạt Tài Khoản</title>
    <style>
        body {
            font-family: 'Be Vietnam Pro', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            max-width: 200px;
            height: auto;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo">
            <img src="{{ asset('images/logo/logo-site.webp') }}" alt="{{ config('app.name') }}">
        </div>
        
        <h1>Kích Hoạt Tài Khoản</h1>
        
        <div class="content">
            <p>Xin chào <strong>{{ $user->full_name }}</strong>,</p>
            
            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>{{ config('app.name') }}</strong>.</p>
            
            <p>Để hoàn tất quá trình đăng ký và kích hoạt tài khoản, vui lòng nhấp vào nút bên dưới:</p>
            
            <div class="button-container">
                <a href="{{ $activationUrl }}" class="button">Kích Hoạt Tài Khoản</a>
            </div>
            
            <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt của bạn:</p>
            <p style="word-break: break-all; color: #007bff;">{{ $activationUrl }}</p>
            
            <div class="warning">
                <strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 24 giờ. Nếu bạn không yêu cầu tạo tài khoản này, vui lòng bỏ qua email này.
            </div>
        </div>
        
        <div class="footer">
            <p>Trân trọng,<br>{{ config('app.name') }}</p>
            <p style="font-size: 12px; color: #999;">Email này được gửi tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>

