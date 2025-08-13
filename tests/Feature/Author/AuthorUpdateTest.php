<?php

namespace Tests\Feature\Author;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test author can be loaded for editing
     */
    public function test_author_can_be_loaded_for_editing(): void
    {
        $author = Author::factory()->create();

        $response = $this->get("/authors/{$author->id}/edit");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $author->id,
            'last_name' => $author->last_name,
            'first_name' => $author->first_name,
            'middle_name' => $author->middle_name,
        ]);
    }

    /**
     * Test author can be updated with valid data
     */
    public function test_author_can_be_updated_with_valid_data(): void
    {
        $author = Author::factory()->create();
        
        $updateData = [
            'last_name' => 'Updated Last',
            'first_name' => 'Updated First',
            'middle_name' => 'Updated Middle',
        ];

        $response = $this->ajaxPut("/authors/{$author->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Author updated successfully',
        ]);

        $this->assertDatabaseHas('authors', array_merge(['id' => $author->id], $updateData));
    }

    /**
     * Test author update fails with invalid data
     */
    public function test_author_update_fails_with_invalid_data(): void
    {
        $author = Author::factory()->create();
        
        $updateData = [
            'last_name' => 'Up', // Too short
            'first_name' => '', // Empty
        ];

        $response = $this->ajaxPut("/authors/{$author->id}", $updateData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['last_name', 'first_name']);
    }

    /**
     * Test updating non-existent author returns 404
     */
    public function test_updating_non_existent_author_returns_404(): void
    {
        $updateData = [
            'last_name' => 'Test',
            'first_name' => 'Test',
        ];

        $response = $this->ajaxPut('/authors/999', $updateData);

        $response->assertStatus(404);
    }

    /**
     * Helper method for AJAX PUT requests
     */
    private function ajaxPut(string $uri, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->put($uri, $data);
    }
}
