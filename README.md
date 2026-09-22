<p align="center">
  <img src="public/images/EventlyLOGO/logo.png" alt="Evently Logo" width="200">
</p>

Sistem pengelolaan event berbasis web untuk membantu organisasi sekolah mengelola event (seminar, workshop, lomba, pelatihan) secara terstruktur, terpusat, dan mudah dipantau — lengkap dengan pendaftaran peserta dan hak akses berbasis role.

Dibangun sebagai bagian dari Sumatif Tengah Semester — LKPD Laravel Client Brief 01.

---

## Daftar Isi

- [Daftar Isi](#daftar-isi)
- [Latar Belakang](#latar-belakang)
- [Fitur](#fitur)
- [Role \& Hak Akses](#role--hak-akses)
- [Tech Stack](#tech-stack)
- [Struktur Database](#struktur-database)
- [Instalasi](#instalasi)
- [Struktur Role \& Middleware](#struktur-role--middleware)
- [Kompetensi yang Diterapkan](#kompetensi-yang-diterapkan)

---

## Latar Belakang

Sebelumnya, pengelolaan event di lingkungan sekolah dilakukan lewat beberapa media berbeda sehingga informasi event, peserta, jadwal, dan status pendaftaran sulit dipantau secara terpusat. Evently hadir sebagai satu sistem terpadu untuk menyelesaikan masalah tersebut.

## Fitur

- **Manajemen Event** — create, edit, hapus, dan lihat detail event lengkap dengan kategori, jadwal, kapasitas, dan status
- **Pendaftaran Event** — peserta dapat browsing event, melihat detail, dan mendaftar dengan validasi otomatis (cek kapasitas, cek duplikat pendaftaran, cek status event)
- **Riwayat Pendaftaran** — peserta dapat memantau status pendaftarannya sendiri (pending / approved / rejected)
- **Kelola Peserta** — panitia dapat melihat, mencari, dan memfilter peserta per event, serta melakukan approve/reject
- **Search, Filter & Pagination** — tersedia di halaman list event dan list peserta
- **Role-Based Access Control** — 3 level akses (admin, panitia, peserta) dengan custom middleware
- **Dashboard per Role** — ringkasan data disesuaikan dengan tanggung jawab masing-masing role

## Role & Hak Akses

| Role | Tanggung Jawab |
|---|---|
| **Admin** | Kelola seluruh event, kategori, dan user lintas sistem |
| **Panitia** | Kelola event miliknya sendiri, kelola & approve/reject peserta pada event tersebut |
| **Peserta** | Browse event, mendaftar, memantau status pendaftaran sendiri |

## Tech Stack

- **Framework:** Laravel
- **Template Engine:** Blade
- **Database:** MySQL
- **Auth:** Laravel Breeze
- **Styling:** *(sesuaikan — misal Tailwind CSS)*

## Struktur Database

Evently terdiri dari 4 entity utama:

```
categories (1) ──── (M) events
users (1) ──── (M) events                 [organizer_id]

users (M) ──── (M) events   →  dijembatani oleh registrations
    users (1) ──── (M) registrations
    events (1) ──── (M) registrations
```

| Tabel | Keterangan |
|---|---|
| `users` | Data akun & role (admin/panitia/peserta) |
| `categories` | Kategori event |
| `events` | Data event: judul, deskripsi, lokasi, jadwal, kapasitas, status |
| `registrations` | Data pendaftaran peserta ke event, beserta status approval |

## Instalasi

```bash
# Clone repository
git clone <repo-url> evently
cd evently

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate
```

Sesuaikan kredensial database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=evently
DB_USERNAME=root
DB_PASSWORD=
```

Lanjutkan setup:

```bash
# Buat database "evently" di MySQL terlebih dahulu, lalu:
php artisan migrate

# (opsional) seed data awal
php artisan db:seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

## Struktur Role & Middleware

Pembatasan akses diterapkan menggunakan custom middleware `CheckRole` yang dipasang pada route group masing-masing:

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // route khusus admin
});

Route::middleware(['auth', 'role:panitia'])->group(function () {
    // route khusus panitia
});

Route::middleware(['auth', 'role:peserta'])->group(function () {
    // route khusus peserta
});
```

## Kompetensi yang Diterapkan

- MVC
- Blade Templating
- Authentication & Authorization
- Eloquent ORM & Relasi Database
- ERD
- Custom Middleware
- Validation & FormRequest
- Search, Filter & Pagination
- N+1 Query Prevention / Eager Loading

---

**Dibuat oleh:** Zentristan Vitadi — XI-3