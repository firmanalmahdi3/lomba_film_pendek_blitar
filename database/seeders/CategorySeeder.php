<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kategori Masyarakat', 'icon' => '👥', 'description' => 'Kebangkitan di Kota Blitar: Semangat – Potensi – Perjuangan Menuju Kota Masa Depan'],
            ['name' => 'Kategori Pelajar',    'icon' => '🎓', 'description' => 'Ruang Digital Aman untuk Generasi Muda'],
            ['name' => 'Kategori KIM',        'icon' => '📡', 'description' => 'Kebangkitan di Kota Blitar: Semangat – Potensi – Perjuangan Menuju Kota Masa Depan'],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'icon'        => $cat['icon'],
                'description' => $cat['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}