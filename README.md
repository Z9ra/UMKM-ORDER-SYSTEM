# 🛒 UMKM Order System

Sistem manajemen order berbasis web untuk UMKM dengan tampilan POS (Point of Sale) modern, dibangun dengan Laravel, Breeze Authentication, dan Tailwind CSS.

---

## 📋 Deskripsi

UMKM Order System adalah aplikasi web full-stack multi-user yang memudahkan pemilik UMKM mengelola menu, menerima order online maupun onsite, serta memantau laporan order melalui dashboard admin yang lengkap. Setiap user memiliki data yang terpisah sehingga cocok digunakan oleh banyak UMKM sekaligus.

---

## ✨ Fitur

- **POS Interface** — Tampilan input order bergaya Point of Sale dengan card menu per kategori
- **Multi Menu per Order** — Pelanggan bisa memesan lebih dari satu menu sekaligus
- **Tipe Order** — Mendukung order Online (dengan WhatsApp & alamat) dan Onsite/Offline
- **Manajemen Menu** — CRUD menu lengkap dengan gambar, kategori, harga, dan detail
- **Dashboard Admin** — Laporan order dengan summary cards dan tabel lengkap
- **Detail Order** — Halaman detail per order dengan rincian item yang dipesan
- **Update Status Order** — Admin dapat mengubah status langsung dari dashboard
- **Edit & Hapus Order** — Dengan konfirmasi sebelum menghapus
- **Log Penghapusan** — Setiap order yang dihapus dicatat otomatis beserta nama admin dan waktu
- **Jam Input Otomatis** — Waktu order masuk tercatat otomatis dalam WIB
- **Export Excel** — Laporan order & log penghapusan dalam 2 sheet terpisah
- **Export PDF** — Laporan order siap cetak
- **Authentication** — Login, Register dengan verifikasi email 2 langkah via Gmail
- **Multi-user** — Data setiap user terpisah, tidak bisa diakses user lain
- **Responsive UI** — Tampilan nyaman di desktop maupun mobile

---

## 🛠️ Tech Stack

| Layer           | Teknologi                           |
| --------------- | ----------------------------------- |
| Backend         | PHP 8.2, Laravel 12                 |
| Authentication  | Laravel Breeze                      |
| Frontend        | HTML, Tailwind CSS (CDN), Alpine.js |
| Database        | MySQL                               |
| Template Engine | Blade                               |
| Export Excel    | Maatwebsite/Laravel-Excel           |
| Export PDF      | Barryvdh/Laravel-DomPDF             |
| Email           | Gmail SMTP                          |

---

## ⚙️ Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM
- XAMPP (atau web server lainnya)
- Akun Gmail dengan App Password

### Langkah Instalasi

**1. Clone repository**

```bash
git clone https://github.com/username/umkm-order-system.git
cd umkm-order-system
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install NPM dependencies**

```bash
npm install
```

**4. Salin file environment**

```bash
cp .env.example .env
```

**5. Generate application key**

```bash
php artisan key:generate
```

**6. Konfigurasi database & email di file `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_order_system
DB_USERNAME=root
DB_PASSWORD=

APP_TIMEZONE=Asia/Jakarta

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD="xxxx xxxx xxxx xxxx"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="emailanda@gmail.com"
MAIL_FROM_NAME="UMKM Order System"
```

**7. Buat database**

```sql
CREATE DATABASE umkm_order_system;
```

**8. Jalankan migrasi**

```bash
php artisan migrate
```

**9. Buat storage link untuk gambar**

```bash
php artisan storage:link
```

**10. Buat akun admin pertama**

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@umkm.com',
    'password' => bcrypt('password123'),
    'email_verified_at' => now(),
]);
```

**11. Jalankan server**

```bash
php artisan serve
```

---

## 🚀 Penggunaan

Setelah server berjalan, buka browser dan akses:

| Halaman           | URL                                 | Akses          |
| ----------------- | ----------------------------------- | -------------- |
| Login             | `http://127.0.0.1:8000/login`       | Publik         |
| Register          | `http://127.0.0.1:8000/register`    | Publik         |
| Dashboard         | `http://127.0.0.1:8000/dashboard`   | Login required |
| Input Order (POS) | `http://127.0.0.1:8000/input-order` | Login required |
| Manajemen Menu    | `http://127.0.0.1:8000/menus`       | Login required |
| Profile           | `http://127.0.0.1:8000/profile`     | Login required |

---

## 📁 Struktur Project

```
umkm-order-system/
├── app/
│   ├── Exports/
│   │   ├── OrdersExport.php
│   │   ├── OrdersSheet.php
│   │   └── DeletionLogsSheet.php
│   ├── Http/Controllers/
│   │   ├── OrderController.php
│   │   ├── MenuController.php
│   │   └── ProfileController.php
│   └── Models/
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Menu.php
│       ├── DeletionLog.php
│       └── User.php
├── database/migrations/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── guest.blade.php
│   │   └── navigation.blade.php
│   ├── auth/
│   ├── menus/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── orders/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── exports/
│       └── orders-pdf.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
└── .env
```

---

## 🗄️ Struktur Database

**Tabel `users`** — Data akun admin

**Tabel `menus`** — Data menu UMKM

| Kolom       | Tipe    | Keterangan                |
| ----------- | ------- | ------------------------- |
| id_menu     | VARCHAR | Primary key (PKG001, dst) |
| user_id     | BIGINT  | Foreign key ke users      |
| nama_menu   | VARCHAR | Nama menu                 |
| kategori    | VARCHAR | Kategori menu             |
| gambar_menu | VARCHAR | Path gambar               |
| detail_menu | TEXT    | Deskripsi menu            |
| harga_menu  | DECIMAL | Harga satuan              |

**Tabel `orders`** — Data pesanan

| Kolom          | Tipe    | Keterangan                   |
| -------------- | ------- | ---------------------------- |
| id_pesanan     | VARCHAR | Primary key (OD001, dst)     |
| user_id        | BIGINT  | Foreign key ke users         |
| nama_pelanggan | VARCHAR | Nama pelanggan               |
| nomor_whatsapp | VARCHAR | No. WA (online only)         |
| alamat         | TEXT    | Alamat (online only)         |
| tipe_order     | ENUM    | online / onsite              |
| total_pesanan  | INT     | Total item dipesan           |
| total_harga    | DECIMAL | Total harga                  |
| status         | ENUM    | pending/proses/selesai/batal |
| jam_input      | TIME    | Jam order masuk              |

**Tabel `order_items`** — Detail item per order

| Kolom      | Tipe    | Keterangan            |
| ---------- | ------- | --------------------- |
| id         | BIGINT  | Primary key           |
| id_pesanan | VARCHAR | Foreign key ke orders |
| id_menu    | VARCHAR | Foreign key ke menus  |
| nama_menu  | VARCHAR | Nama menu saat order  |
| harga_menu | DECIMAL | Harga saat order      |
| jumlah     | INT     | Jumlah dipesan        |
| subtotal   | DECIMAL | Harga x jumlah        |

**Tabel `deletion_logs`** — Log penghapusan order

---

## 🗺️ Roadmap

- [x] Form order online
- [x] Dashboard laporan order
- [x] Update status order
- [x] Authentication (Login/Register) dengan Laravel Breeze
- [x] Email verification via Gmail
- [x] Navbar navigasi lengkap dengan dropdown logout
- [x] Edit & hapus order
- [x] Log penghapusan otomatis
- [x] Jam input otomatis (WIB)
- [x] Export Excel (2 sheet) & PDF
- [x] Multi-user (data terpisah per user)
- [x] Manajemen menu dengan gambar & kategori
- [x] POS Interface (card menu per kategori)
- [x] Multiple menu per order
- [x] Tipe order Online & Onsite
- [x] Detail order per pesanan
- [ ] Ganti UI ke Bootstrap
- [ ] Sistem donasi
- [ ] Notifikasi WhatsApp otomatis (Fonnte API)
- [ ] Deploy ke hosting

---

## 👤 Developer

**Zeinan Ramadan**

- GitHub: [@ZeinanRamadan](https://github.com/)
- LinkedIn: [Zeinan Ramadan](https://linkedin.com/)

---

## 📄 Lisensi

Project ini dibuat untuk keperluan portofolio dan pengembangan bisnis IT Solution.

---

> Dibuat dengan ❤️ sebagai bagian dari perjalanan membangun IT Solution Company
