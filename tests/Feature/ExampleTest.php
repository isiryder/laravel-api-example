<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public function testGetRoot()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testGetBooks()
   {
       $response = $this->get('/api/books');

       $response->assertStatus(200);
       $response->assertJson([
            'name' => '1984',
            'year' => '1948',
    ]);
    }
}
