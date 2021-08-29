<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Models\Library;
use Tests\TestCase;

class LibrariesTest extends TestCase
{
    use WithFaker;
 
    public function testCreateOneLibrarySuccesfully()
    {
         $payload = [
             'name' => $this->faker->name,
             'address' => $this->faker->address
         ];
 
         $this->json('POST', 'api/libraries', $payload)
             ->assertStatus(Response::HTTP_CREATED);
 
         $this->assertDatabaseHas('libraries', $payload);
     }
 
     public function testDeleteNonExistingLibrary()
     {
         $this->json('DELETE', 'api/libraries/0')
         ->assertStatus(Response::HTTP_NOT_FOUND);
     }
 
     public function testDeleteOneLibrarySuccesfully()
     {
         $author = Library::create([
             'name' => $this->faker->name,
             'address' => $this->faker->address
         ]);
 
         $this->json('DELETE', 'api/libraries/' . $author->id)
         ->assertStatus(Response::HTTP_OK);
 
         $this->assertSoftDeleted('libraries', ['id' => $author->id]);
      }
 
     public function testFindOneLibrarySuccesfully()
     {
         $author = Library::create([
             'name' => $this->faker->name,
             'address' => $this->faker->address
         ]);
 
         $this->assertDatabaseHas('libraries', $author->toArray());
 
         $this->json('GET', 'api/libraries/' . $author->id)
         ->assertStatus(Response::HTTP_OK);
     }
 
    public function testFindNonExistingLibrary()
    {
         $this->json('GET', 'api/libraries/0')
         ->assertStatus(Response::HTTP_NOT_FOUND);
    }
 
    public function testUpdateOneLibrarySuccesfully()
    {
         $author = Library::create([
             'name' => $this->faker->name,
             'address' => $this->faker->address
         ]);
 
         $payload = $author->toArray();
 
         $this->assertDatabaseHas('libraries', $payload);
 
         $payload['name'] .= ' modified!';
         $this->json('PUT', 'api/libraries/' . $author->id, $payload)
             ->assertStatus(Response::HTTP_NO_CONTENT);
 
         $this->assertDatabaseHas('libraries', $payload);
     }
 
     public function testUpdateNonExistingLibrary()
     {
          $payload = [
             'name' => $this->faker->name,
             'address' => $this->faker->address
          ];
 
          $this->json('PUT', 'api/libraries/0', $payload)
              ->assertStatus(Response::HTTP_NO_CONTENT);
 
          $this->assertDatabaseMissing('libraries', $payload);
      }
 
     public function testListLibrariesSuccesfully()
     {
         $response = $this->json('GET', 'api/libraries')
             ->assertStatus(Response::HTTP_OK)
             ->assertJsonStructure([
                 [
                     "id",
                     "name",
                     "address",
                 ]
             ]);
     }
 }
 