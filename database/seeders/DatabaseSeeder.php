<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    protected $toTruncate = ['authors', 'books', 'libraries', 'book_library'];

    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        Model::reguard();

        $this->call(AuthorsTableSeeder::class);
        $this->call(LibrariesTableSeeder::class);
        $this->call(BooksTableSeeder::class);
    }
}
