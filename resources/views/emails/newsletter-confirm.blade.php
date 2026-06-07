<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đăng ký</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:'Inter',Arial,sans-serif;color:#1f2937;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
<tr><td align="center">
<table role="presentation" width="620" cellspacing="0" cellpadding="0" style="max-width:620px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden;">
<tr><td style="background:linear-gradient(120deg,#0f766e,#0ea5e9);padding:28px 32px;color:#fff;">
<h1 style="margin:0;font-size:24px;">Xác nhận đăng ký nhận bản tin</h1>
<p style="margin:8px 0 0 0;font-size:14px;opacity:.95;">TechSewing sẽ gửi bạn các cập nhật bài viết, giải pháp và khuyến mãi mới.</p>
</td></tr>
<tr><td style="padding:30px 32px;">
<p style="margin:0 0 16px;line-height:1.65;">Cảm ơn bạn đã đăng ký với email <strong>{{ $subscriber->email }}</strong>.</p>
<p style="margin:0 0 22px;line-height:1.65;">Vui lòng bấm nút bên dưới để kích hoạt đăng ký nhận tin:</p>
<p style="margin:0 0 24px;"><a href="{{ $confirmUrl }}" style="display:inline-block;padding:12px 24px;background:#0f766e;color:#fff;text-decoration:none;border-radius:10px;font-weight:bold;">Xác nhận đăng ký</a></p>
<p style="margin:0;line-height:1.6;color:#4b5563;font-size:13px;">Nếu bạn không đăng ký, hãy bỏ qua email này.</p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
