<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực đăng ký dùng thử</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h1 style="color: #0B4069; margin-top: 0;">Xác thực đăng ký dùng thử</h1>
    </div>

    <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
        <p>Xin chào <strong>{{ $user->full_name }}</strong>,</p>

        <p>Cảm ơn bạn đã đăng ký dùng thử <strong>{{ $toolName }}</strong>!</p>

        <p>Để hoàn tất đăng ký và bắt đầu sử dụng, vui lòng nhấp vào nút bên dưới để xác thực:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="display: inline-block; background: #0B4069; color: #fff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Xác thực đăng ký
            </a>
        </div>

        <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt:</p>
        <p style="word-break: break-all; color: #0B4069; background: #f8f9fa; padding: 10px; border-radius: 4px;">
            {{ $verificationUrl }}
        </p>

        <p style="color: #666; font-size: 14px; margin-top: 30px;">
            <strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 24 giờ. Nếu bạn không yêu cầu đăng ký này, vui lòng bỏ qua email này.
        </p>

        <p style="margin-top: 30px;">Trân trọng,<br>
        <strong>{{ config('app.name') }}</strong></p>
    </div>

    <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
        <p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
    </div>
</body>
</html>

