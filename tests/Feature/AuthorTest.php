<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Author;
use Tests\TestCase;

class AuthorTest extends TestCase
{
   use WithFaker;

   public function testCreateOneAuthorSuccesfully()
   {
        $payload = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
        ];

        $this->json('POST', 'api/authors', $payload)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('authors', $payload);
    }

    public function testDeleteNonExistingAuthor()
    {
        $this->json('DELETE', 'api/authors/0')
        ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteOneAuthorSuccesfully()
    {
        $author = Author::create([
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
        ]);

        $this->json('DELETE', 'api/authors/' . $author->id)
        ->assertStatus(Response::HTTP_OK);

        $this->assertSoftDeleted('authors', ['id' => $author->id]);
     }

    public function testFindOneAuthorSuccesfully()
    {
        $author = Author::create([
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
        ]);

        $this->assertDatabaseHas('authors', $author->toArray());

        $this->json('GET', 'api/authors/' . $author->id)
        ->assertStatus(Response::HTTP_OK);
    }

   public function testFindNonExistingAuthor()
   {
        $this->json('GET', 'api/authors/0')
        ->assertStatus(Response::HTTP_NOT_FOUND);
   }

   public function testUpdateOneAuthorSuccesfully()
   {
        $author = Author::create([
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
        ]);

        $payload = $author->toArray();

        $this->assertDatabaseHas('authors', $payload);

        $payload['name'] .= ' modified!';
        $this->json('PUT', 'api/authors/' . $author->id, $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('authors', $payload);
    }

    public function testUpdateNonExistingAuthor()
    {
         $payload = [
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'genre' => $this->faker->word
         ];

         $this->json('PUT', 'api/authors/0', $payload)
             ->assertStatus(Response::HTTP_NO_CONTENT);

         $this->assertDatabaseMissing('authors', $payload);
     }

    public function testListAuthorsSuccesfully()
    {
        $response = $this->json('GET', 'api/authors')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                [
                    "id",
                    "name",
                    "birth_date",
                ]
            ]);
    }
}
