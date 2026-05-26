<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Faker\Factory as Faker;
use App\Models\User;
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
        \App\Models\User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
            
        // 2. Insert Kategori Event
            $category = \App\Models\Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);

        $category2 = \App\Models\Category::firstOrCreate([
            'name' => 'Entertaiment',
            'slug' => 'entertaiment',
        ]);

        \App\Models\Category::firstOrCreate([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);

        \App\Models\Category::firstOrCreate([
            'name' => 'Content Creator',
            'slug' => 'content-creator',
        ]);
            
        // 3. Insert Sampel Events
        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
            'date' => '2026-05-05 10:00:00',
            'location' => 'Inkubator Amikom',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI & FUTURE TECH SUMMIT 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);
		
		MahasiswaModel::truncate();
            
        $faker = DB::transaction(function () { 
            $faker = Faker::create('id_ID');
			
            for($i = 1; $i <= 10; $i++){
                MahasiswaModel::create([
					// 'nim'   		=> Str::random(8),
					'mahasiswa_nm'	=> $faker->name ,
                    'nim'			=> date('y') .date('m') .str_pad($i, 4 , "0" ,STR_PAD_LEFT),
                    'nik'			=> $faker->nik,
					'alamat'        => $faker->streetAddress,
                    'telepon'		=> $faker->phoneNumber,
                    'email'         => $faker->email,
					'tanggal_lahir'	=> $faker->dateTimeThisCentury()->format('Y-m-d'),
                    'gender'     	=> $faker->randomElement($array = array ('male', 'female')) == 'male' ? '1' : '2',
                    'created_id' 	=> 'admin',
                ]);
            }
        });

		// $this->call(MahasiswaTableSeeder::class);
        // $this->call(Database\Seeders\MahasiswaSeeder::class);
    }
}
