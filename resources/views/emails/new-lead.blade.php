<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yêu cầu liên hệ mới</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; color: #2563eb; }
        .info-row { margin-bottom: 10px; }
        .info-row strong { display: inline-block; width: 150px; color: #555; }
        .message-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 5px; margin-top: 10px; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TechSewing - Yêu cầu liên hệ mới</h2>
        <p>Hệ thống vừa ghi nhận một yêu cầu liên hệ mới từ website.</p>
    </div>

    <div class="info">
        <div class="info-row"><strong>Họ và tên:</strong> {{ $lead->name }}</div>
        <div class="info-row"><strong>Số điện thoại:</strong> {{ $lead->phone }}</div>
        <div class="info-row"><strong>Email:</strong> {{ $lead->email ?? 'Không có' }}</div>
        <div class="info-row"><strong>Công ty:</strong> {{ $lead->company ?? 'Không có' }}</div>
        <div class="info-row"><strong>Nhu cầu:</strong> {{ $lead->interest ?? 'Không xác định' }}</div>
        
        <div class="info-row" style="margin-top: 20px;"><strong>Nội dung lời nhắn:</strong></div>
        <div class="message-box">
            {!! nl2br(e($lead->message)) !!}
        </div>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động từ hệ thống website TechSewing.</p>
        <p><a href="{{ url('/admin/leads/'.$lead->id.'/edit') }}">Đăng nhập Admin để xem chi tiết</a></p>
    </div>
</body>
</html>
