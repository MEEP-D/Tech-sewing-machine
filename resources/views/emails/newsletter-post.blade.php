<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,sans-serif;color:#1f2937;">
@php($post = $campaign->post)
@php($emailThumbnailUrl = $post->email_thumbnail_url)
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
<tr><td align="center">
<table role="presentation" width="640" cellspacing="0" cellpadding="0" style="max-width:640px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden;">
<tr><td style="background:#0f172a;padding:26px 30px;color:#fff;">
<div style="font-size:12px;letter-spacing:1px;text-transform:uppercase;opacity:.75;">TechSewing Newsletter</div>
<h1 style="margin:8px 0 0 0;font-size:24px;line-height:1.35;">{{ $post->title }}</h1>
</td></tr>
@if($emailThumbnailUrl)
<tr><td><img src="{{ $emailThumbnailUrl }}" alt="{{ $post->title }}" style="width:100%;display:block;max-height:320px;object-fit:cover;"></td></tr>
@endif
<tr><td style="padding:28px 30px;">
<p style="margin:0 0 14px;color:#374151;line-height:1.7;">{{ $post->excerpt ?: 'Bai viet moi da duoc cap nhat tren website TechSewing.' }}</p>
<p style="margin:0 0 22px;color:#4b5563;line-height:1.75;">Nhan vao nut duoi day de xem chi tiet noi dung moi nhat.</p>
<p style="margin:0 0 30px;"><a href="{{ $post->url }}" style="display:inline-block;padding:12px 24px;border-radius:10px;background:#0ea5e9;color:#fff;text-decoration:none;font-weight:700;">Doc bai viet</a></p>
<hr style="border:none;border-top:1px solid #e5e7eb;margin:0 0 20px 0;">
<p style="margin:0;font-size:12px;color:#6b7280;line-height:1.6;">Ban dang nhan email nay vi da dang ky tu website TechSewing.<br>Nen khong muon nhan nua? <a href="{{ $unsubscribeUrl }}" style="color:#0ea5e9;">Huy dang ky</a></p>
</td></tr>
</table>
</td></tr>
</table>
</body>
</html>
