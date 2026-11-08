# Panduan Standarisasi Deployment VPS & Lingkungan Produksi — SIMAGANG

Dokumen ini merupakan panduan teknis resmi untuk menerapkan **Prioritas 1 (Kesiapan Lingkungan Produksi & VPS)** pada sistem SIMAGANG (Pemerintah Kabupaten Tuban).

---

## 1. Konfigurasi Environment File (`.env`) di VPS

Salin `.env.example` menjadi `.env` pada direktori root proyek di server VPS, kemudian sesuaikan parameter berikut:

```ini
APP_NAME="SIMAGANG Tuban"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://simang.tubankab.go.id

# DATABASE PRODUCTION
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simang_production
DB_USERNAME=simang_user
DB_PASSWORD=PasswordKuatAcak123!

# QUEUE & BACKGROUND WORKER
# Wajib gunakan 'database' atau 'redis' agar pengiriman email notifikasi/Resend
# tidak memblokir (freeze/timeout) tombol di browser pengguna.
QUEUE_CONNECTION=database

# CACHE STORE & RATE LIMITING
# Menghindari I/O harddisk saat memverifikasi limit percobaan login brute force.
CACHE_STORE=database
SESSION_DRIVER=database
FILESYSTEM_DISK=public

# PENGIRIMAN EMAIL (RESEND / SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_YourResendApiKeyHere
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=simagang@tubankab.go.id
MAIL_FROM_NAME="SIMAGANG Pemerintah Kabupaten Tuban"
```

---

## 2. Instalasi Paket Dependensi Linux (Ubuntu / Debian VPS)

Jalankan perintah berikut di terminal VPS untuk memastikan PHP, ekstensi gambar, dan font DomPDF terpasang lengkap:

```bash
# Update repository
sudo apt update && sudo apt upgrade -y

# Paket esensial PHP 8.2+
sudo apt install -y php-cli php-fpm php-mysql php-mbstring php-xml php-curl php-zip php-bcmath

# EKSTENSI KRITIKAL UNTUK PDF & GAMBAR (DomPDF Kop Surat):
sudo apt install -y php-gd php-imagick fonts-dejavu fonts-dejavu-core fonts-freefont-ttf

# DAEMON BACKGROUND TASK (Untuk menjalankan Queue Worker otomatis)
sudo apt install -y supervisor nginx
```

> **Catatan Penting DomPDF:** Paket `php-gd` dan font `fonts-dejavu` menjamin ekspor rekap PDF bimbingan dan pendaftaran magang tidak terpotong atau mengalami error font di lingkungan Linux VPS.

---

## 3. Konfigurasi Background Queue Worker (Supervisor)

Agar email otomatis terkirim tanpa jeda saat pendaftar diterima/ditolak/sertifikat terbit, pasang daemon Supervisor:

1. Buat file konfigurasi worker:
   ```bash
   sudo nano /etc/supervisor/conf.d/simang-worker.conf
   ```

2. Tempelkan konfigurasi berikut (sesuaikan path folder aplikasi):
   ```ini
   [program:simang-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/simang/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/www/simang/storage/logs/worker.log
   stopwaitsecs=3600
   ```

3. Aktifkan worker:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start simang-worker:*
   ```

4. Cek status worker kapan saja:
   ```bash
   sudo supervisorctl status
   ```

---

## 4. Standarisasi Keamanan Server & Web Server (Nginx)

### A. Security Headers
Aplikasi SIMAGANG sudah dilengkapi dengan middleware `App\Http\Middleware\SecurityHeaders` yang secara otomatis menginjeksi:
- `X-Frame-Options: SAMEORIGIN` (Pencegah *Clickjacking*)
- `X-Content-Type-Options: nosniff` (Pencegah *MIME sniffing*)
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=(self)`
- `Strict-Transport-Security: max-age=31536000; includeSubDomains` (HSTS saat HTTPS aktif)

### B. Konfigurasi Nginx Server Block (`/etc/nginx/sites-available/simang`)
```nginx
server {
    listen 80;
    server_name simang.tubankab.go.id;
    root /var/www/simang/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    # Batas ukuran upload berkas pendaftaran (CV / Surat Pengantar / Proposal)
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### C. Pasang Sertifikat SSL Gratis (Certbot)
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d simang.tubankab.go.id
```

---

## 5. Perintah Rutin Deployment (Post-Pull di VPS)

Setiap kali melakukan pembaruan kode (`git pull`) ke VPS, jalankan urutan perintah ini:

```bash
# 1. Jalankan migrasi database
php artisan migrate --force

# 2. Buat symlink storage publik
php artisan storage:link

# 3. Optimasi Cache Kompilasi Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Restart Background Queue Worker agar memuat kode terbaru
sudo supervisorctl restart simang-worker:*
```

