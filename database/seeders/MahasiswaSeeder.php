<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Faker\Factory as Faker;
use App\Models\MahasiswaModel;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		// \DB::table('mahasiswa')->delete();
		/* MahasiswaModel::truncate();
            
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
        }); */
		
		//--Factory
		// \App\Models\Mahasiswa::factory(10)->create();
		MahasiswaModel::truncate();
		MahasiswaModel::factory(20)->create();
    }
}
