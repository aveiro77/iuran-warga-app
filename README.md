<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Aplikasi Iuran Warga

Aplikasi **Iuran Warga** adalah sistem berbasis web yang dikembangkan menggunakan **Laravel** dan **Filament Admin Panel**.  
Tujuannya adalah untuk membantu pengelolaan keuangan kas warga — baik **pemasukan** maupun **pengeluaran** — secara lebih mudah, rapi, dan transparan.

## Fitur Utama

### 💰 Manajemen Pemasukan
- Form input untuk mencatat setiap pemasukan warga.  
- Setiap transaksi memiliki **kode pemasukan** unik.  
- Dapat dikategorikan berdasarkan jenis pemasukan, misalnya:
  - Iuran rutin mingguan  
  - Donasi warga  

### 💸 Manajemen Pengeluaran
- Form input untuk mencatat semua pengeluaran kas.  
- Setiap pengeluaran memiliki **kode pengeluaran** unik.  
- Dilengkapi kategori pengeluaran, contohnya:
  - **Biaya kebersihan** (contoh: beli sapu, bayar tukang sampah)  
  - **Biaya kegiatan** (contoh: acara 17 Agustus, rapat warga)  
  - **Perawatan lingkungan & fasum** (contoh: penerangan jalan)
    
### 📊 Laporan & Riwayat Transaksi
- Rekap data pemasukan dan pengeluaran.  
- Menampilkan saldo kas terkini.  
- Filter berdasarkan periode waktu atau kategori.  

## ⚙️ Teknologi yang Digunakan

- [Laravel 11+](https://laravel.com) — Framework backend utama.  
- [Filament Admin Panel](https://filamentphp.com) — CRUD generator & dashboard admin.  
- [Tailwind CSS](https://tailwindcss.com) — Styling cepat dan responsif.  
- [MySQL](https://www.mysql.com) — Database penyimpanan data keuangan warga. 

## 🚀 Cara Instalasi

- git clone https://github.com/username/iuran-warga-app.git
- cd iuran-warga-app
- composer install
- npm install && npm run build
- cp .env.example .env
- Atur konfigurasi database di file .env
- php artisan migrate --seed
- php artisan serve


📜 **Lisensi**

Aplikasi ini dikembangkan menggunakan Laravel yang dilisensikan di bawah MIT License.
Silakan gunakan, modifikasi, dan kembangkan untuk kebutuhan komunitas warga Anda.

🧩 **Tentang Proyek Ini**

Dibuat dengan ❤️ oleh Bligosoft
Fokus pada solusi perangkat lunak sederhana dan bermanfaat bagi masyarakat.
