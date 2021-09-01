<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Author;
use App\Models\Book;
use App\Models\Library;
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

   public function testCreateOneBookWithAuthorSuccesfully()
   {
        $payload = [
            'name' => $this->faker->name,
            'year' => $this->faker->year,
            "author" => [
                "name" => $this->faker->name,
                "birth_date" => $this->faker->date,
                "genre" => $this->faker->word
            ],
        ];

        $response = $this->json('POST', 'api/books', $payload)
            ->assertStatus(Response::HTTP_CREATED);

        $this->json('GET', 'api/books/' . $response->json()['book']['id'])
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

    public function testCreateOneBookWithLibrarySuccesfully()
    {
         $payload = [
             'name' => $this->faker->name,
             'year' => $this->faker->year,
             "libraries" => [
                 0 => [
                    "name" => $this->faker->name,
                    "address" => $this->faker->date,
                 ]
             ],
         ];
 
         $response = $this->json('POST', 'api/books', $payload)
             ->assertStatus(Response::HTTP_CREATED);
 
         $this->json('GET', 'api/books/' . $response->json()['book']['id'])
         ->assertStatus(Response::HTTP_OK)
         ->assertJsonStructure([
             "id",
             "name",
             "year",
             "libraries" => [
                0 => [
                    "name",
                    "address",
                ]
            ]
         ]);
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

    public function testUpdateOneBookWithAuthorSuccesfully()
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

        $payload = $book->toArray();
        $payload['author'] = $author->toArray();

        $payload['name'] .= ' modified!';
        $payload['author']['name'] .= ' modified!';

        $this->json('PUT', 'api/books/' . $book->id, $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $response = $this->json('GET', 'api/books/' . $book->id)
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
        $this->assertEquals($response->json()['author']['id'], $payload['author']['id']);
        $this->assertEquals($response->json()['author']['name'], $payload['author']['name']);
    }

    public function testUpdateOneBookWithLibrarySuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $libraries = Library::create([
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ]);

        $book->libraries()->save($libraries);

        $payload = $book->toArray();
        $payload['libraries'] = [
            0 => $libraries->toArray()
        ];

        $payload['name'] .= ' modified!';
        $payload['libraries'][0]['name'] .= ' modified!';

        $this->json('PUT', 'api/books/' . $book->id, $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $response = $this->json('GET', 'api/books/' . $book->id)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "id",
            "name",
            "year",
            "author_id",
            "libraries" => [
                0 => [
                    "name",
                    "address",
                ]
            ]
        ]);    
        $this->assertEquals($response->json()['libraries'][0]['id'], $payload['libraries'][0]['id']);
        $this->assertEquals($response->json()['libraries'][0]['name'], $payload['libraries'][0]['name']);
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

    public function testFindOneBookWithLibrarySuccesfully()
    {
        $book = Book::create([
            'name' => $this->faker->name,
            'year' => $this->faker->year
        ]);

        $libraries = Library::create([
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ]);

        $book->libraries()->save($libraries);

        $book->save();

        $this->assertDatabaseHas('books', $book->toArray());

        $this->json('GET', 'api/books/' . $book->id)
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            "id",
            "name",
            "year",
            "libraries" => [
                0 => [
                    "name",
                    "address",
                ]
            ]
        ]);
    }
 
    public function testFindOneBookWithAuthorAndLibrarySuccesfully()
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

        $libraries = Library::create([
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ]);

        $book->libraries()->save($libraries);

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
            ],
            "libraries" => [
                0 => [
                    "name",
                    "address",
                ]
            ]
        ]);
    }
}
