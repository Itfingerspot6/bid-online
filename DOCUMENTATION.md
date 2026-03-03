# 📖 Dokumentasi Lengkap BidOnline

BidOnline adalah platform lelang online modern yang dibangun dengan Laravel 12, Filament, Reverb, dan Alpine.js. Dokumen ini merangkum semua fitur utama yang tersedia dalam aplikasi.

---

## 🛠 Tech Stack
- **Framework**: Laravel 12
- **UI Framework**: Tailwind CSS
- **Interaktivitas**: Alpine.js
- **Admin Panel**: Filament v3
- **Pembayaran**: Midtrans Snap Integration
- **Real-time Engine**: Laravel Reverb + Laravel Echo
- **Asset Bundler**: Vite

---

## 🔥 Fitur Utama

### 1. Manajemen Lelang (Auction)
- **Dashboard Lelang**: Halaman utama yang menampilkan lelang aktif dengan kategori dan pencarian.
- **Multi-Image Support**: Seller bisa mengunggah beberapa foto untuk satu item lelang.
- **Watchlist**: User bisa menyimpan lelang favorit mereka ke daftar pantau.
- **Timer Dinamis**: Countdown waktu tersisa yang akurat sampai level detik.

### 2. Sistem Penawaran (Bidding)
- **Manual Biding**: Input jumlah bid manual dengan validasi saldo dan kenaikan minimal.
- **⚡ Buy Now Price**: Fitur untuk memenangkan lelang secara instan jika penawaran mencapai harga beli sekarang.
- **🤖 Proxy Bidding (Auto-bid)**: User bisa mengatur harga maksimal. Sistem akan otomatis menawar $1 (atau minimal increment) di atas penawar lain sampai batas maksimal tercapai.
- **Auto-Refund**: Jika user kalah bid (outbid), saldo yang terpotong akan otomatis dikembalikan ke akun mereka.

### 3. Real-time Experience (Live Auction)
- **Live Price Updates**: Harga lelang di halaman detail berubah secara instan saat ada bid baru tanpa perlu refresh.
- **Live Bid History**: Tabel riwayat bid bertambah secara otomatis saat ada aktivitas baru.
- **Toast Notifications**: Notifikasi pop-up real-time muncul saat ada bid baru dari user lain.

### 4. Transaksi & Saldo (Finance)
- **Deposit Midtrans**: Isi saldo otomatis menggunakan API Midtrans (mendukung VA, E-wallet, Credit Card).
- **History Transaksi**: Catatan lengkap aliran dana (Deposit, Bid, Refund, Win).
- **Saldo Terkunci**: Saldo hanya akan dipotong secara permanen jika user menang (saat bid, saldo "dipegang" sementara oleh sistem).

### 5. Push Notifications
- **Outbid Notification**: Notifikasi saat penawaran user dilewati orang lain.
- **Win Notification**: Notifikasi saat user memenangkan lelang.
- **Deposit Notification**: Notifikasi saat transaksi deposit berhasil.
- **Bell Dashboard**: Menu notifikasi interaktif di navbar.

### 6. Admin Panel (Filament)
- **Moderasi Lelang**: Admin bisa membatalkan atau mengubah status lelang.
- **Bid Approval**: Melalui Panel Admin `/admin/bids`, admin memiliki kontrol penuh atas setiap bid.
- **User & Category Management**: Kelola pengguna dan kategori barang dengan mudah.

---

## 🚀 Alur Pengguna (User Flow)

1.  **Registrasi & Deposit**: User mendaftar dan mengisi saldo melalui menu "Transaksi".
2.  **Cari Lelang**: User mencari item yang diinginkan di halaman utama atau lewat kategori.
3.  **Place Bid**: User memasukkan bid manual atau mengaktifkan Auto-bid.
4.  **Wait for Results**: User memantau lelang secara real-time. Jika di-outbid, saldo kembali otomatis.
5.  **Winning**: Jika hingga waktu habis user menjadi penawar tertinggi, lelang berakhir dan item menjadi milik user.

---

## 💻 Cara Menjalankan Fitur Real-time (Local)
Untuk menjalankan fitur update harga otomatis, jalankan perintah berikut secara bersamaan:
```bash
# Jalankan Core Server
php artisan serve

# Jalankan Vite (CSS/JS)
npm run dev

# Jalankan Reverb (WebSocket)
php artisan reverb:start
```
