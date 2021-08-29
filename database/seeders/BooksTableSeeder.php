<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::truncate();

        $faker = \Faker\Factory::create();

        for ($i=0; $i < 50; $i++){
            Book::create([
                'name' => $faker->name,
                'year' => $faker->year
            ]);
        }
    }
}
