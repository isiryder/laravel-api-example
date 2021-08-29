<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Author;
use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
   use WithFaker;

   public function testCreateOneBookSuccesfully()
   {
        $payload = [
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ];

        $this->json('POST', 'api/books', $payload)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('books', $payload);
    }

    public function testDeleteNonExistingBook()
    {
        $this->json('DELETE', 'api/books/0')
        ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteOneBookSuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $this->json('DELETE', 'api/books/' . $book->id)
        ->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted('books', ['id' => $book->id]);
     }

    public function testFindOneBookSuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $this->assertDatabaseHas('books', $book->toArray());

        $this->json('GET', 'api/books/' . $book->id)
        ->assertStatus(Response::HTTP_OK);
    }

    public function testFindNonExistingBook()
    {
            $this->json('GET', 'api/books/0')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateOneBookSuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $payload = $book->toArray();

        $this->assertDatabaseHas('books', $payload);

        $payload['name'] .= ' modified!';
        $this->json('PUT', 'api/books/' . $book->id, $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('books', $payload);
    }

    public function testUpdateNonExistingBook()
    {
         $payload = [
            'name' => $this->faker->name,
            'year' => $this->faker->year
         ];

         $this->json('PUT', 'api/books/0', $payload)
             ->assertStatus(Response::HTTP_NO_CONTENT);

         $this->assertDatabaseMissing('books', $payload);
     }

    public function testListBooksSuccesfully()
    {
        $response = $this->json('GET', 'api/books')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                [
                    "id",
                    "name",
                    "year"
                ]
            ]);
    }

    public function testFindOneBookWithAuthorSuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $author = Author::create([
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
        ]);

        $book->author_id = $author->id;

        $book->save();

        $this->assertDatabaseHas('books', $book->toArray());

        $this->json('GET', 'api/books/' . $book->id)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "id",
            "name",
            "year",
            "author_id",
            "author" => [
                "name",
                "birth_date",
                "genre"
            ]
        ]);
    }

}
