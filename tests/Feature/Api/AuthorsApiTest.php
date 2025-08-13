<?php

namespace Tests\Feature\Api;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorsApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authors API endpoint returns JSON
     */
    public function test_authors_api_returns_json(): void
    {
        $authors = Author::factory()->count(3)->create();

        $response = $this->get('/api/authors');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
        
        foreach ($authors as $author) {
            $response->assertJsonFragment([
                'id' => $author->id,
                'last_name' => $author->last_name,
                'first_name' => $author->first_name,
                'middle_name' => $author->middle_name,
            ]);
        }
    }

    /**
     * Test authors API returns empty array when no authors
     */
    public function test_authors_api_returns_empty_array_when_no_authors(): void
    {
        $response = $this->get('/api/authors');

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * Test authors API orders by last name
     */
    public function test_authors_api_orders_by_last_name(): void
    {
        Author::factory()->create(['last_name' => 'Zebra']);
        Author::factory()->create(['last_name' => 'Alpha']);

        $response = $this->get('/api/authors');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertEquals('Alpha', $data[0]['last_name']);
        $this->assertEquals('Zebra', $data[1]['last_name']);
    }
}
