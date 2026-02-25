# Cara Seeding Database

## Seeding Pertama Kali (Fresh Install)
Gunakan ini jika database masih kosong atau ingin reset total:

```bash
php artisan migrate:fresh --seed
```

**PERHATIAN:** Ini akan menghapus SEMUA data termasuk user yang sudah ada!

## Seeding Tanpa Menghapus User
Jika ingin seed ulang auction dan category tapi tetap mempertahankan user:

### 1. Hapus hanya auction dan bid
```bash
php artisan tinker --execute="\App\Models\Bid::query()->delete(); \App\Models\Auction::query()->delete();"
```

### 2. Seed ulang category dan auction
```bash
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=AuctionSeeder
```

**CATATAN PENTING:**
- Admin TIDAK AKAN punya bid sama sekali
- Hanya user biasa yang akan jadi bidder
- Setiap user biasa akan punya banyak bid di berbagai auction
- Setiap auction akan punya 5-10 bids dari user yang berbeda-beda

## Seed Spesifik

### Seed User Saja
```bash
php artisan db:seed --class=UserSeeder
```

### Seed Category Saja
```bash
php artisan db:seed --class=CategorySeeder
```

### Seed Auction Saja
```bash
php artisan db:seed --class=AuctionSeeder
```

## Default User Credentials

### Admin
- Email: admin@example.com
- Password: admin123

### Regular Users
- Email: budi@example.com, siti@example.com, ahmad@example.com, dewi@example.com, rizky@example.com
- Password: password123

## Tips
- UserSeeder menggunakan `updateOrCreate` jadi aman dijalankan berulang kali
- Jika user sudah ada, hanya akan update data tanpa membuat duplikat
- Balance user akan di-random setiap kali seed
