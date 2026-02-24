<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik',
            'Handphone & Tablet',
            'Laptop & Komputer',
            'Kamera & Foto',
            'Audio & Speaker',
            'Fashion Pria',
            'Fashion Wanita',
            'Jam Tangan',
            'Tas & Dompet',
            'Sepatu & Sandal',
            'Otomotif',
            'Motor & Aksesori',
            'Mobil & Aksesori',
            'Rumah & Taman',
            'Furnitur',
            'Peralatan Dapur',
            'Seni & Koleksi',
            'Perhiasan & Emas',
            'Antik & Vintage',
            'Olahraga & Outdoor',
            'Buku & Alat Tulis',
            'Mainan & Hobi',
            'Musik & Instrumen',
            'Gaming',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}