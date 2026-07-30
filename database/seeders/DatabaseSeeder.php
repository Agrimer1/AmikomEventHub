<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\MahasiswaModel;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name' => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 2. Insert Kategori Event
        $category = Category::firstOrCreate([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $category2 = Category::firstOrCreate([
            'name' => 'Entertaiment',
            'slug' => 'entertaiment',
        ]);

        Category::firstOrCreate([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);

        Category::firstOrCreate([
            'name' => 'Content Creator',
            'slug' => 'content-creator',
        ]);

        // 3. Insert Sampel Events
        Event::firstOrCreate(
            ['title' => 'Jazz Night 2025'],
            [
                'category_id' => $category2->id,
                'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
                'date' => '2026-05-10 19:00:00',
                'location' => 'Amikom Baru',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-1.png',
            ]
        );

        Event::firstOrCreate(
            ['title' => 'Hackaton - Unleash Your Inner Developer'],
            [
                'category_id' => $category->id,
                'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
                'date' => '2026-05-05 10:00:00',
                'location' => 'Inkubator Amikom',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-2.png',
            ]
        );

        Event::firstOrCreate(
            ['title' => 'AI & FUTURE TECH SUMMIT 2026'],
            [
                'category_id' => $category->id,
                'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
                'date' => '2026-05-01 13:00:00',
                'location' => 'Cinema Unit 6',
                'price' => 50000,
                'stock' => 100,
                'poster_path' => 'posters/event-3.png',
            ]
        );

        if (class_exists(\Faker\Factory::class)) {
            $faker = \Faker\Factory::create('id_ID');
            for ($i = 1; $i <= 5; $i++) {
                MahasiswaModel::firstOrCreate(
                    ['nim' => date('y') . date('m') . str_pad($i, 4, "0", STR_PAD_LEFT)],
                    [
                        'mahasiswa_nm' => $faker->name,
                        'nik'          => $faker->nik,
                        'alamat'       => $faker->streetAddress,
                        'telepon'      => $faker->phoneNumber,
                        'email'        => $faker->email,
                        'tanggal_lahir' => $faker->dateTimeThisCentury()->format('Y-m-d'),
                        'gender'       => $i % 2 === 0 ? '1' : '2',
                        'created_id'   => 'admin',
                    ]
                );
            }
        }
    }
}
