<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\Models\MahasiswaModel;

/**
 * @extends Factory<Model>
 */
class MahasiswaModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('id_ID');
		static $counter = 1;
		
        return [
            // 'nim'   		=> Str::random(8),
			'mahasiswa_nm'	=> $faker->name ,
			'nim'			=> date('y') .date('m') .str_pad($counter++, 4 , "0" ,STR_PAD_LEFT),
			'nik'			=> $faker->nik,
			'alamat'        => $faker->streetAddress,
			'telepon'		=> $faker->phoneNumber,
			'email'         => $faker->email,
			'tanggal_lahir'	=> $faker->dateTimeThisCentury()->format('Y-m-d'),
			'gender'     	=> $faker->randomElement($array = array ('male', 'female')) == 'male' ? '1' : '2',
			'created_id' 	=> 'admin',
        ];
    }
}
