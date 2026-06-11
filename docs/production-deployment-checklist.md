# Production deployment checklist

Checklist nay gom cac phan can cau hinh sau khi deploy Laravel app len hosting/VPS.

## 1. Bien moi truong

Copy `.env.production.example` thanh `.env` tren server va dien gia tri that:

- `APP_KEY`: chay `php artisan key:generate --show`, copy ket qua vao `.env`.
- `APP_URL` va `APP_PUBLIC_URL`: domain production, vi du `https://techsewing.example`.
- `APP_DEBUG=false`.
- `DB_*`: thong tin MySQL production.
- `FILESYSTEM_DISK=public` neu upload anh vao `storage/app/public`.
- `MAIL_*`: SMTP mac dinh. Admin van co the cau hinh SMTP trong Filament sau.

Sau khi sua `.env`, chay:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 2. Migration va du lieu mau

Chay migration:

```bash
php artisan migrate --force
```

Neu can tao admin/du lieu mau:

```bash
php artisan db:seed --force
```

Khong dung `migrate:fresh` tren production neu da co du lieu, vi lenh do xoa toan bo bang.

## 3. Storage upload

App dang upload anh qua disk `public`, nen can tao symlink:

```bash
php artisan storage:link
```

Thu muc web server phai tro document root vao `public/`, khong tro vao root project.

## 4. Queue worker

App dang dung `QUEUE_CONNECTION=database`. Cac tac vu can worker:

- Gui newsletter sau khi bai viet duoc publish.
- Gui mail dang ky/xac nhan neu mail duoc day vao queue.
- Mail thong bao lead moi.
- Export cua Filament neu duoc cau hinh chay qua queue.

Import san pham trong admin dang chay bang connection `sync` va co cache lock `product-imports`, nen khong can queue worker rieng cho thao tac import san pham. Neu admin import nhieu file gan nhau, request vao sau se doi request import truoc xu ly xong.

Tren VPS co Supervisor, tao process:

```ini
[program:tech-sewing-queue]
command=php /path/to/project/artisan queue:work database --sleep=3 --tries=3 --timeout=120
directory=/path/to/project
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/queue-worker.log
```

Sau moi lan deploy:

```bash
php artisan queue:restart
```

Tren shared hosting khong co Supervisor, dat cron moi phut:

```bash
* * * * * cd /path/to/project && php artisan queue:work database --stop-when-empty --tries=3 --timeout=120 >> storage/logs/queue-cron.log 2>&1
```

## 5. Scheduler

Scheduler dang goi `newsletter:dispatch-due` moi phut de dua cac bai viet da den gio publish vao queue newsletter.

Dat cron moi phut:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> storage/logs/schedule.log 2>&1
```

Neu scheduler khong chay, bai viet publish theo lich se khong tu dong tao newsletter campaign.

## 6. SMTP / Mail

Co 2 lop cau hinh mail:

- `.env`: cau hinh SMTP fallback cho Laravel.
- Admin Filament > Cai dat website > SMTP / Mail: cau hinh SMTP dong luu trong bang `settings`, chi can dien khi muon override `.env`.

Sau khi dien SMTP trong admin, bam gui email test. Cac truong can co:

- SMTP host, port, encryption.
- Username/password.
- From email phai trung domain/nguon duoc SMTP cho phep.

Voi Gmail/Google Workspace, dung App Password hoac SMTP relay hop le, khong dung mat khau tai khoan thuong.

## 7. Telescope va Pulse

Production nen tat mac dinh:

```env
TELESCOPE_ENABLED=false
PULSE_ENABLED=false
```

Chi bat tam thoi khi debug, va can bao ve duong dan admin.

## 8. Ben thu ba

Hien repo co package Cloudinary va S3/Flysystem, nhung upload trong admin dang ghi vao disk `public`.

Neu muon dung S3-compatible storage:

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Neu muon dung Cloudinary, can noi lai cac FileUpload/ImageService sang Cloudinary disk hoac uploader rieng. Chi dien `CLOUDINARY_URL` se chua tu dong doi luong upload hien tai.

## 9. Lenh kiem tra nhanh sau deploy

```bash
php artisan about
php artisan migrate:status
php artisan queue:failed
php artisan schedule:list
php artisan route:list --except-vendor
```

Kiem tra them:

- Upload anh trong admin co hien o frontend.
- Gui SMTP test thanh cong.
- Dang ky newsletter nhan duoc email xac nhan.
- Tao/publish bai viet moi, jobs xuat hien trong bang `jobs` roi worker xu ly het.
