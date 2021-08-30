<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\Library;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Book::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $faker = \Faker\Factory::create();

        $library = Library::create([
            'name' => $faker->name,
            'address' => $faker->address
        ]);

        for ($i=0; $i < 50; $i++){
            $book = Book::create([
                'name' => $faker->name,
                'year' => $faker->year,
                'author_id' => 1,
            ]);

            $book->libraries()->save($library);

            $book->save();
        }
    }
}
