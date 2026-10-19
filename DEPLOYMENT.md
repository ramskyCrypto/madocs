# Panduan Deploy Madocs ke Server/Hosting

Proyek ini butuh **PHP 8.2+, Composer, Node.js, dan MySQL**. Cocok untuk VPS
(dengan Nginx/Apache) atau shared hosting yang mendukung Laravel (mis.
Niagahoster, DomaiNesia dengan cPanel + PHP 8.2, atau platform seperti
Railway/Hostinger VPS).

## 1. Checklist sebelum online

- [x] Bug `scopeVisibleTo` di `app/Models/Document.php` sudah diperbaiki (kondisi OR untuk Guru/Kepala Sekolah kini dibungkus closure, sehingga filter & pencarian tetap berlaku).
- [ ] `APP_ENV=production` dan `APP_DEBUG=false` — **wajib**, supaya detail error teknis tidak terlihat pengunjung.
- [ ] `APP_KEY` digenerate ulang khusus server produksi (`php artisan key:generate`), jangan memakai key dari lokal.
- [ ] Domain sudah mengarah ke server dan sudah pasang **HTTPS** (mis. Let's Encrypt via Certbot, atau SSL bawaan hosting).
- [ ] Database produksi dibuat, kredensial diisi di `.env` (lihat `.env.production.example`).
- [ ] Folder `storage/` dan `bootstrap/cache/` permission `775`, dimiliki user web server (`chown -R www-data:www-data storage bootstrap/cache` di Linux).
- [ ] **Jangan** jalankan `php artisan db:seed` di produksi — seeder berisi 5 akun demo (admin/kepsek/guru/staff/siswa) dengan password yang sama persis (`password123`) dan diketahui publik. Buat akun admin pertama secara manual (lihat langkah 5 di bawah).
- [ ] Backup database terjadwal (cron `mysqldump` harian, atau fitur backup hosting).
- [ ] `php artisan config:cache`, `route:cache`, `view:cache` dijalankan setelah deploy untuk performa lebih baik (jangan jalankan `config:cache` kalau masih sering ubah `.env`).

## 2. Langkah deploy (VPS/server sendiri)

```bash
# 1. Upload kode (git clone / scp / unzip) ke server, contoh ke /var/www/madocs

# 2. Install dependency
composer install --optimize-autoloader --no-dev
npm install && npm run build

# 3. Siapkan .env
cp .env.production.example .env
php artisan key:generate
# lalu edit .env: isi domain, kredensial DB, SMTP

# 4. Migrasi database (TANPA --seed di produksi)
php artisan migrate --force

# 5. Buat akun admin pertama secara manual, misalnya lewat tinker:
php artisan tinker
>>> \App\Models\User::create(['name'=>'Admin','username'=>'admin','email'=>'admin@sekolah.sch.id','password'=>bcrypt('ganti-dengan-password-kuat'),'role'=>'admin','status'=>'aktif','email_verified_at'=>now()])

# 6. Link storage publik (untuk aset publik, bukan dokumen — dokumen disimpan
#    di disk privat 'documents' dan diakses lewat route terproteksi)
php artisan storage:link

# 7. Cache untuk performa
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Pastikan storage & cache writable
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Arahkan **document root** web server ke folder `public/`, bukan ke root
proyek — ini langkah yang sering terlewat dan menyebabkan source code bisa
diakses langsung dari browser.

## 3. Shared hosting (cPanel)

- Pakai fitur "Setup Node.js/PHP App" kalau tersedia, atau upload manual lalu
  jalankan perintah di atas lewat Terminal cPanel / SSH.
- Document root domain diarahkan ke `public_html -> madocs/public` (banyak
  panel punya opsi symlink/alias untuk ini).
- Pastikan versi PHP di cPanel diset ke 8.2 atau lebih baru (Select PHP
  Version).

## 4. Setelah online

- Coba login dengan tiap peran (admin, guru, staf, siswa) dan pastikan hak
  akses dokumen (publik/internal/privat) sudah sesuai.
- Coba upload dokumen besar mendekati batas `MADOCS_MAX_UPLOAD_MB` untuk
  memastikan `upload_max_filesize` dan `post_max_size` di `php.ini` server
  tidak lebih kecil dari batas itu.
- Pantau `storage/logs/laravel.log` beberapa hari pertama untuk menangkap
  error yang tidak muncul saat development.
