<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Kategori;
use App\Models\Penerbit;
use App\Models\Author;
use App\Models\Anggota;
use App\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID'); // Faker Indonesia

        // ============================================
        // 1. Seed 5 Admin
        // ============================================
        $admins = [];
        for ($i = 1; $i <= 10; $i++) {
            $admins[] = Admin::create([
                'nama' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
            ]);
        }

        // ============================================
        // 2. Seed 5 Kategori
        // ============================================
        $kategoriNames = [
            'Teknologi' => 'Buku-buku tentang teknologi dan pemrograman',
            'Fiksi' => 'Novel dan karya fiksi lainnya',
            'Sains' => 'Buku-buku ilmu pengetahuan',
            'Sejarah' => 'Buku-buku sejarah dan budaya',
            'Bisnis' => 'Buku tentang bisnis dan manajemen',
        ];

        $kategoris = [];
        foreach ($kategoriNames as $nama => $deskripsi) {
            $kategoris[] = Kategori::create([
                'nama_kategori' => $nama,
                'deskripsi' => $deskripsi,
            ]);
        }

        // ============================================
        // 3. Seed 5 Penerbit
        // ============================================
        $penerbits = [];
        for ($i = 1; $i <= 10; $i++) {
            $penerbits[] = Penerbit::create([
                'nama_penerbit' => $faker->company() . ' Publisher',
                'alamat' => $faker->address(),
                'email' => $faker->unique()->companyEmail(),
            ]);
        }

        // ============================================
        // 4. Seed 5 Author
        // ============================================
        $authors = [];
        for ($i = 1; $i <= 10; $i++) {
            $authors[] = Author::create([
                'nama_author' => $faker->name(),
                'biografi' => $faker->paragraph(3),
                'email' => $faker->unique()->safeEmail(),
            ]);
        }

        // ============================================
        // 5. Seed 5 Anggota
        // ============================================
        $anggotas = [];
        // Use firstOrCreate so seeding is idempotent (re-running won't cause unique constraint errors)
        for ($i = 1; $i <= 10; $i++) {
            $nim = '2024' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $anggota = Anggota::firstOrCreate(
                ['nim' => $nim], // lookup by nim (unique)
                [
                    'nama' => $faker->name(),
                    'email' => $faker->unique()->safeEmail(),
                    'alamat' => $faker->address(),
                    'status_keanggotaan' => $faker->randomElement(['Aktif', 'Non-Aktif']),
                    'foto_profil' => null,
                ]
            );

            $anggotas[] = $anggota;
        }

        // ============================================
        // 6. Seed 5 Buku
        // ============================================
        $bukus = [];
        for ($i = 1; $i <= 10; $i++) {
            $bukus[] = Buku::create([
                'judul' => $faker->sentence(rand(3, 6)),
                'cover_buku' => 'cover_' . $i . '.jpg',
                'isbn' => $faker->unique()->isbn13(),
                'tahun_terbit' => $faker->year(),
                'stok' => $faker->numberBetween(1, 10),
                'kategori_id' => $kategoris[array_rand($kategoris)]->id,
                'penerbit_id' => $penerbits[array_rand($penerbits)]->id,
            ]);
        }

        // ============================================
        // 7. Attach Authors ke Buku (Relasi Many-to-Many)
        // ============================================
        foreach ($bukus as $buku) {
            // Setiap buku akan punya 1-3 author secara random
            $randomAuthors = $faker->randomElements($authors, rand(1, 3));
            $authorIds = array_map(fn($author) => $author->id, $randomAuthors);
            $buku->authors()->attach($authorIds);
        }
    }
}