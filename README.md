# Madocs — Management Documents School

Madocs adalah sistem manajemen dokumen sekolah berbasis **Laravel 12** yang memungkinkan sekolah menyimpan, mencari, memfilter, mengunduh, mengarsipkan, dan melacak aktivitas dokumen secara aman dengan kontrol akses berbasis peran (RBAC).

## Fitur Utama

- Autentikasi aman (hash password, rate limiting, proteksi CSRF, session regeneration, akun nonaktif otomatis ditolak)
- Role: Admin, Kepala Sekolah, Guru, Staff/Tata Usaha, Siswa — permission diverifikasi di backend melalui Policy & Middleware, bukan hanya disembunyikan di tampilan
- CRUD dokumen lengkap dengan upload aman (validasi MIME/ekstensi/ukuran, nama file di-generate ulang, file disimpan di disk `documents` yang tidak publik)
- Download & preview PDF dilayani lewat controller yang memverifikasi otorisasi setiap request
- Pencarian & filter dokumen (nama, nomor, deskripsi, kategori, status, format, uploader, rentang tanggal) — berbasis query database, bekerja bersama pagination
- Manajemen kategori dengan proteksi penghapusan jika masih dipakai dokumen
- Manajemen pengguna (tambah/edit/nonaktifkan/reset password/soft delete) dengan proteksi admin tidak bisa menghapus akun sendiri
- Dashboard dengan statistik & grafik yang diambil langsung dari database (tidak hardcode)
- Riwayat aktivitas/audit log untuk seluruh aksi penting
- Desain responsif penuh (sidebar jadi drawer di mobile, tabel jadi card di mobile)
- Identitas visual Madocs: krem `#FCE9CF` (dominan), biru `#2A82F6` (semi dominan), oranye `#F15A24` (aksen)

## Requirements

- PHP >= 8.2 dengan ekstensi umum Laravel (mbstring, openssl, pdo, tokenizer, xml, ctype, json, fileinfo, gd/imagick)
- Composer 2.x
- Node.js >= 18 & NPM (untuk build asset Tailwind/Vite)
- MySQL 8 / MariaDB 10.6+

## Instalasi

### 1. Clone & install dependency

```bash
composer install
npm install
```

### 2. Salin file environment

Linux/Mac:
```bash
cp .env.example .env
```

Windows (PowerShell/CMD):
```powershell
copy .env.example .env
```

### 3. Generate application key

```bash
php artisan key:generate
```

### 4. Konfigurasi `.env`

Sesuaikan bagian berikut dengan environment Anda:

```env
APP_NAME=Madocs
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=madocs
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

MADOCS_MAX_UPLOAD_MB=10
MADOCS_ALLOWED_EXT=pdf,doc,docx,xls,xlsx,ppt,pptx
```

Buat database kosong bernama `madocs` (atau sesuai `DB_DATABASE`) di MySQL/MariaDB Anda terlebih dahulu.

### 5. Migrasi & seeding database

```bash
php artisan migrate --seed
```

Perintah ini menjalankan seluruh migration dari database kosong dan mengisi data demo (5 akun, 10+ kategori, 18 dokumen, 25+ log aktivitas).

### 6. Buat symbolic link storage

```bash
php artisan storage:link
```

### 7. Build asset frontend

Untuk development:
```bash
npm run dev
```

Untuk production:
```bash
npm run build
```

### 8. Jalankan server

```bash
php artisan serve
```

Buka `http://localhost:8000` di browser.

## Akun Demo

Semua akun demo menggunakan kata sandi: **`password123`**

| Role            | Username | Email                  |
|-----------------|----------|-------------------------|
| Admin           | `admin`  | admin@madocs.sch.id     |
| Kepala Sekolah  | `kepsek` | kepsek@madocs.sch.id    |
| Guru            | `guru`   | guru@madocs.sch.id      |
| Staff/TU        | `staff`  | staff@madocs.sch.id     |
| Siswa           | `siswa`  | siswa@madocs.sch.id     |

> Segera ganti kata sandi akun demo sebelum digunakan di lingkungan produksi.

## Menjalankan Test

```bash
php artisan test
```

atau

```bash
./vendor/bin/phpunit
```

Test menggunakan koneksi SQLite in-memory (lihat `phpunit.xml`), jadi tidak menyentuh database MySQL Anda.

## Struktur Peran & Hak Akses (Ringkasan)

| Aksi                          | Admin | Kepala Sekolah | Guru | Staff | Siswa |
|-------------------------------|:-----:|:---------------:|:----:|:-----:|:-----:|
| Lihat dashboard               | ✅ | ✅ | ✅ | ✅ | ✅ |
| Lihat/cari/filter dokumen     | ✅ | ✅ | ✅ | ✅ | ✅ (publik saja) |
| Upload dokumen                | ✅ | ❌ | ✅ | ✅ | ❌ |
| Edit dokumen                  | ✅ | ❌ | Milik sendiri | ✅ | ❌ |
| Hapus dokumen                 | ✅ | ❌ | ❌ | ✅ | ❌ |
| Kelola kategori                | ✅ | ❌ | ❌ | ✅ | ❌ |
| Kelola pengguna                | ✅ | ❌ | ❌ | ❌ | ❌ |
| Lihat riwayat aktivitas        | ✅ | ❌ | ❌ | ❌ | ❌ |
| Pengaturan sistem              | ✅ | ❌ | ❌ | ❌ | ❌ |

Seluruh aturan di atas diterapkan lewat `Policy` (`DocumentPolicy`, `CategoryPolicy`, `UserPolicy`), `Gate` (`manage-settings`, `view-activities`), dan middleware `role:` — bukan hanya menyembunyikan tombol di Blade.

## Keamanan File Upload

- Ekstensi & MIME divalidasi lewat `StoreDocumentRequest` / `UpdateDocumentRequest`
- Nama file asli **tidak pernah** dipakai sebagai nama file di storage — sistem membuat nama UUID baru (`DocumentService::storeFile`)
- File disimpan di disk `documents` yang **tidak publik**; akses hanya melalui route `documents.download` / `documents.preview` yang diverifikasi Policy
- Saat file diganti ketika edit: file baru disimpan lebih dulu, baru file lama dihapus, mencegah file orphan maupun dokumen kehilangan file jika terjadi kegagalan di tengah proses
- Saat dokumen dihapus, file fisik ikut dihapus dari storage

## Catatan Teknis

- Kategori tidak dapat dihapus jika masih dipakai dokumen (dicek di controller **dan** di level database lewat `restrictOnDelete()`)
- Pengguna yang masih memiliki riwayat dokumen tidak dihapus permanen — sistem otomatis melakukan **soft delete** untuk menjaga integritas data historis
- Admin tidak dapat menghapus atau menonaktifkan akunnya sendiri
- Semua query dokumen memakai eager loading (`with(['category', 'user'])`) untuk menghindari N+1 query
- Pagination dipakai di semua listing (dokumen, kategori, pengguna, aktivitas) dan tetap mempertahankan parameter search/filter (`withQueryString()`)

## Lisensi

Proyek ini dibuat untuk kebutuhan internal sekolah dan dapat dikembangkan lebih lanjut sesuai kebutuhan.
