<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đổi Mật Khẩu</title>
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
            background-color: #28a745;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }
        .button:hover {
            background-color: #218838;
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
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
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
        
        <h1>Xác Nhận Đổi Mật Khẩu</h1>
        
        <div class="content">
            <p>Xin chào <strong>{{ $user->full_name }}</strong>,</p>
            
            <p>Chúng tôi nhận được yêu cầu đổi mật khẩu cho tài khoản của bạn tại <strong>{{ config('app.name') }}</strong>.</p>
            
            <p>Để xác nhận và hoàn tất việc đổi mật khẩu, vui lòng nhấp vào nút bên dưới:</p>
            
            <div class="button-container">
                <a href="{{ $confirmationUrl }}" class="button">Xác Nhận Đổi Mật Khẩu</a>
            </div>
            
            <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt của bạn:</p>
            <p style="word-break: break-all; color: #28a745;">{{ $confirmationUrl }}</p>
            
            <div class="warning">
                <strong>Lưu ý:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Liên kết này sẽ hết hạn sau 60 phút.</li>
                    <li>Nếu bạn không yêu cầu đổi mật khẩu, vui lòng bỏ qua email này và liên hệ với chúng tôi ngay lập tức.</li>
                    <li>Mật khẩu của bạn sẽ không thay đổi cho đến khi bạn nhấp vào liên kết trên và xác nhận.</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>Trân trọng,<br>{{ config('app.name') }}</p>
            <p style="font-size: 12px; color: #999;">Email này được gửi tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>

