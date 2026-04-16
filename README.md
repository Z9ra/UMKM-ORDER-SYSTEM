# 🛒 UMKM Order System

Sistem manajemen order berbasis web untuk UMKM, dibangun dengan Laravel, Breeze Authentication, dan Tailwind CSS.

---

## 📋 Deskripsi

UMKM Order System adalah aplikasi web full-stack yang memudahkan pelanggan melakukan pemesanan secara online dan membantu pemilik usaha mengelola serta memantau order masuk melalui dashboard admin yang aman dan informatif.

---

## ✨ Fitur

- **Form Order Online** — Pelanggan dapat mengisi nama, nomor WhatsApp, alamat, dan detail order
- **Dashboard Admin** — Tampilan ringkasan order berdasarkan status (Pending, Proses, Selesai, Batal)
- **Update Status Order** — Admin dapat mengubah status order langsung dari dashboard
- **Authentication (Login/Register)** — Dashboard hanya bisa diakses oleh admin yang sudah login
- **Navbar Navigasi** — Akses Dashboard dan Input Order langsung dari navbar
- **Notifikasi Flash** — Konfirmasi otomatis setiap kali order berhasil ditambahkan atau diupdate
- **Responsive UI** — Tampilan yang nyaman di desktop maupun mobile

---

## 🛠️ Tech Stack

| Layer           | Teknologi                |
| --------------- | ------------------------ |
| Backend         | PHP 8.2, Laravel 12      |
| Authentication  | Laravel Breeze           |
| Frontend        | HTML, Tailwind CSS (CDN) |
| Database        | MySQL                    |
| Template Engine | Blade                    |

---

## ⚙️ Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM
- XAMPP (atau web server lainnya)

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

**6. Konfigurasi database di file `.env`**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_order_system
DB_USERNAME=root
DB_PASSWORD=
```

**7. Buat database**

```sql
CREATE DATABASE umkm_order_system;
```

**8. Jalankan migrasi**

```bash
php artisan migrate
```

**9. Buat akun admin pertama**

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@umkm.com',
    'password' => bcrypt('password123'),
]);
```

**10. Jalankan server**

```bash
php artisan serve
```

---

## 🚀 Penggunaan

Setelah server berjalan, buka browser dan akses:

| Halaman                | URL                               | Akses          |
| ---------------------- | --------------------------------- | -------------- |
| Form Order (Pelanggan) | `http://127.0.0.1:8000`           | Publik         |
| Login Admin            | `http://127.0.0.1:8000/login`     | Publik         |
| Dashboard Admin        | `http://127.0.0.1:8000/dashboard` | Login required |
| Input Order (Admin)    | `http://127.0.0.1:8000/`          | Login required |
| Profile Admin          | `http://127.0.0.1:8000/profile`   | Login required |

---

## 📁 Struktur Project

```
umkm-order-system/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── OrderController.php
│   │       └── ProfileController.php
│   └── Models/
│       ├── Order.php
│       └── User.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       └── create_orders_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── guest.blade.php
│       │   └── navigation.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       └── orders/
│           ├── create.blade.php
│           └── index.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
└── .env
```

---

## 🗄️ Struktur Database

Tabel `users`:

| Kolom      | Tipe      | Keterangan        |
| ---------- | --------- | ----------------- |
| id         | BIGINT    | Primary key       |
| name       | VARCHAR   | Nama admin        |
| email      | VARCHAR   | Email login       |
| password   | VARCHAR   | Password (hashed) |
| created_at | TIMESTAMP | Waktu dibuat      |

Tabel `orders`:

| Kolom          | Tipe      | Keterangan                         |
| -------------- | --------- | ---------------------------------- |
| id             | BIGINT    | Primary key                        |
| nama_pelanggan | VARCHAR   | Nama lengkap pelanggan             |
| nomor_whatsapp | VARCHAR   | Nomor WA pelanggan                 |
| alamat         | TEXT      | Alamat pengiriman                  |
| detail_order   | TEXT      | Rincian pesanan                    |
| total_harga    | DECIMAL   | Total harga (opsional)             |
| status         | ENUM      | pending / proses / selesai / batal |
| created_at     | TIMESTAMP | Waktu order dibuat                 |
| updated_at     | TIMESTAMP | Waktu order diupdate               |

---

## 🗺️ Roadmap

- [x] Form order online
- [x] Dashboard laporan order
- [x] Update status order
- [x] Authentication (Login/Register) dengan Laravel Breeze
- [x] Navbar navigasi lengkap
- [ ] Ganti UI ke Bootstrap
- [ ] Sistem donasi
- [ ] Notifikasi WhatsApp otomatis (Fonnte API)
- [ ] Export laporan ke PDF / Excel
- [ ] AI Chatbot terintegrasi (WhatsApp, Instagram, TikTok)
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
