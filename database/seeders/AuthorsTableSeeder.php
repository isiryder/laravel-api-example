<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Author::truncate();

        $faker = \Faker\Factory::create();

        for ($i=0; $i < 50; $i++){
            Author::create([
                'name' => $faker->name,
                'birth_date' => $faker->date,
                'genre' => $faker->word
            ]);
        }
    }
}
