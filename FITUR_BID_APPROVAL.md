# Fitur Bid Approval System

## Overview
Sistem approval untuk bid dimana setiap bid yang dibuat oleh user harus disetujui oleh admin terlebih dahulu sebelum aktif.

## Fitur Utama

### 1. User Side
- User bisa membuat bid pada lelang aktif
- Bid akan masuk dengan status **pending** (menunggu approval admin)
- User tidak langsung dikenakan potongan saldo saat bid
- User bisa melihat status bid mereka di dashboard:
  - 🟡 **Pending**: Menunggu approval admin
  - 🟢 **Approved**: Bid disetujui dan aktif
  - 🔴 **Rejected**: Bid ditolak admin

### 2. Admin Side (Filament Panel)
Admin memiliki kontrol penuh atas semua bid melalui panel admin:

#### Akses Admin Panel
- URL: `/admin/bids`
- Menu: "Bids" di sidebar admin panel

#### Fitur Admin
1. **View All Bids**: Melihat semua bid dengan filter status
2. **Edit Bid**: Mengubah jumlah bid, status, dll
3. **Delete Bid**: Menghapus bid
4. **Approve Bid**: 
   - Mengubah status jadi approved
   - Memotong saldo user
   - Update harga lelang jika bid lebih tinggi
   - Mencatat transaksi
   - **Auto-close auction**: Jika bid mencapai/melebihi buy_now_price, lelang langsung ended dan user jadi pemenang
5. **Reject Bid**: 
   - Mengubah status jadi rejected
   - Tidak ada potongan saldo

## Database Changes

### Migration: `2026_02_24_100000_add_status_to_bids_table.php`
Menambahkan kolom `status` ke tabel `bids`:
- **pending**: Default, menunggu approval
- **approved**: Disetujui admin
- **rejected**: Ditolak admin

## File Changes

### Models
- `app/Models/Bid.php`: Tambah `status` ke fillable
- `app/Models/Auction.php`: Tambah relationship `approvedBids()` dan update `highestBid()`

### Controllers
- `app/Http/Controllers/BidController.php`: 
  - Bid dibuat dengan status pending
  - Tidak langsung potong saldo user
  - Pesan sukses diubah

### Filament Resources
- `app/Filament/Resources/BidResource.php`: Resource baru untuk manage bids
- `app/Filament/Resources/BidResource/Pages/`: Pages untuk CRUD bids

### Views
- `resources/views/auctions/show.blade.php`: Tambah kolom status di tabel bid history
- `resources/views/dashboard.blade.php`: Tambah badge status di daftar bid user

### Commands
- `app/Console/Commands/CloseExpiredAuctions.php`: Update untuk hanya hitung bid yang approved

## Cara Menggunakan

### Sebagai User
1. Login ke aplikasi
2. Pilih lelang yang aktif
3. Masukkan jumlah bid
4. Klik "Bid Sekarang"
5. Tunggu approval dari admin
6. Cek status di Dashboard

### Sebagai Admin
1. Login ke admin panel (`/admin`)
2. Klik menu "Bids"
3. Lihat daftar bid yang pending
4. Klik tombol "Approve" untuk menyetujui atau "Reject" untuk menolak
5. Atau klik "Edit" untuk mengubah detail bid
6. Atau klik "Delete" untuk menghapus bid

## Testing
```bash
# Jalankan migration
php artisan migrate

# Test create bid sebagai user
# Test approve/reject bid sebagai admin di /admin/bids
```

## Notes
- Hanya bid dengan status **approved** yang dihitung dalam lelang
- Saldo user baru dipotong setelah bid di-approve
- Admin bisa edit bid sesuka hati (jumlah, status, dll)
- Bid yang rejected tidak mempengaruhi saldo user
- **Auto-close**: Jika bid yang di-approve mencapai/melebihi buy_now_price, lelang otomatis ended dan bidder jadi pemenang
- Saat auto-close, semua bidder lain yang kalah akan di-refund otomatis
