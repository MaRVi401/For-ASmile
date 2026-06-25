# For A Smile (FAS) - Platform E-Donasi Bulanan Terintegrasi

For A Smile (FAS) adalah platform pengelolaan donasi digital (*E-Donasi*) berbasis web yang dirancang untuk mengelompokkan berbagai program filantropi ke dalam kampanye donasi bulanan. Sistem ini dilengkapi dengan panel administrasi modern, pengelolaan data yang terintegrasi, serta dukungan pembayaran online melalui Midtrans Payment Gateway.

Proyek ini dibangun menggunakan Laravel dengan pendekatan *clean architecture* dan memanfaatkan berbagai library frontend berbasis CDN sehingga tidak memerlukan instalasi Node.js (`npm install`) untuk dijalankan.

---

## 🚀 Fitur Utama

### 👤 Panel Administrasi

* Kelola Kampanye Donasi Bulanan
* Kelola Program Kerja Donasi
* Monitoring Target dan Progress Dana
* Manajemen Status Kampanye:

  * Draft
  * Upcoming
  * Active
  * Completed
* Laporan dan Riwayat Transaksi Donasi
* Proteksi Integritas Data (Mencegah *Orphan Data*)

### 💳 Sistem Pembayaran

* Integrasi Midtrans Payment Gateway
* Dukungan:

  * GoPay
  * ShopeePay
  * Virtual Account Bank
  * Alfamart
  * Indomaret
* Pembaruan Status Donasi Otomatis melalui Callback Midtrans
* Akumulasi Dana Kampanye Otomatis

### 🎨 User Experience

* SweetAlert2 untuk konfirmasi dan notifikasi
* Alpine.js untuk interaktivitas frontend
* Tabler Icons
* Tailwind CSS v4 melalui CDN

---

## 🛠️ Tech Stack

| Teknologi          | Keterangan             |
| ------------------ | ---------------------- |
| Laravel 13         | Backend Framework      |
| PHP 8.3+           | Server-side Language   |
| Tailwind CSS v4    | Styling                |
| Alpine.js          | Frontend Interactivity |
| SweetAlert2        | Alert & Modal          |
| Midtrans Snap      | Payment Gateway        |
| MySQL / PostgreSQL | Database               |
| Tabler Icons       | Icon Library           |

---

## 📦 Instalasi Lokal

### 1. Clone Repository

```bash
git clone https://github.com/username/for-asmile.git
cd for-asmile
```

### 2. Install Dependency

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example`:

```bash
cp .env.example .env
```

Lalu sesuaikan konfigurasi berikut:

```env
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=for_asmile
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
```

### 4. Generate Key dan Migrasi Database

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Buat Symbolic Link Storage

```bash
php artisan storage:link
```

### 6. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi melalui:

```text
http://127.0.0.1:8000
```

---

## 🌐 Pengujian Callback Midtrans Menggunakan Ngrok

Karena server Midtrans tidak dapat mengakses localhost secara langsung, gunakan Ngrok untuk membuat URL publik sementara.

### Jalankan Laravel

```bash
php artisan serve
```

### Jalankan Ngrok

```bash
ngrok http 8000
```

Contoh hasil:

```text
https://a1b2-34-56.ngrok-free.app
```

### Perbarui APP_URL

```env
APP_URL=https://a1b2-34-56.ngrok-free.app
```

### Konfigurasi Midtrans

Masuk ke:

**Settings → Configuration**

Isi kolom **Payment Notification URL**:

```text
https://a1b2-34-56.ngrok-free.app/midtrans/callback
```

Simpan konfigurasi dan lakukan simulasi pembayaran menggunakan Midtrans Sandbox.

---

## 📂 Struktur Direktori

```text
app/
├── Http/
│   └── Controllers/
│       ├── Admin/
│       │   ├── CampaignController.php
│       │   └── ProgramController.php
│       └── MidtransCallbackController.php
│
├── Models/
│   ├── Campaign.php
│   ├── Program.php
│   └── Transaction.php

bootstrap/
└── app.php

config/
└── services.php

resources/
└── views/
    └── admin/
        ├── layouts/
        │   └── app.blade.php
        ├── campaigns/
        └── programs/

routes/
└── web.php
```

---

## 🔐 Midtrans Callback Endpoint

Contoh endpoint callback:

```php
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle']);
```

Pastikan endpoint ini dapat diakses publik melalui URL Ngrok atau server hosting.

---

## 📄 License

Project ini dilisensikan di bawah **MIT License**.

---

## 👨‍💻 Developer

**For A Smile (FAS)**

Platform E-Donasi Bulanan Terintegrasi berbasis Laravel dan Midtrans untuk mendukung kegiatan sosial dan filantropi secara digital.
