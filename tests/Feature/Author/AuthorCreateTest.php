<?php

namespace Tests\Feature\Author;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test author can be created with valid data
     */
    public function test_author_can_be_created_with_valid_data(): void
    {
        $authorData = [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'middle_name' => 'William',
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Author created successfully',
        ]);

        $this->assertDatabaseHas('authors', $authorData);
    }

    /**
     * Test author can be created without middle name
     */
    public function test_author_can_be_created_without_middle_name(): void
    {
        $authorData = [
            'last_name' => 'Smith',
            'first_name' => 'Jane',
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('authors', array_merge($authorData, ['middle_name' => null]));
    }

    /**
     * Test author creation fails without last name
     */
    public function test_author_creation_fails_without_last_name(): void
    {
        $authorData = [
            'first_name' => 'John',
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Test author creation fails without first name
     */
    public function test_author_creation_fails_without_first_name(): void
    {
        $authorData = [
            'last_name' => 'Doe',
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name']);
    }

    /**
     * Test author creation fails with short last name
     */
    public function test_author_creation_fails_with_short_last_name(): void
    {
        $authorData = [
            'last_name' => 'Do',
            'first_name' => 'John',
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['last_name']);
    }

    /**
     * Test author creation fails with too long names
     */
    public function test_author_creation_fails_with_too_long_names(): void
    {
        $authorData = [
            'last_name' => str_repeat('a', 101),
            'first_name' => str_repeat('b', 101),
            'middle_name' => str_repeat('c', 101),
        ];

        $response = $this->ajaxPost('/authors', $authorData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['last_name', 'first_name', 'middle_name']);
    }

    /**
     * Helper method for AJAX POST requests
     */
    private function ajaxPost(string $uri, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post($uri, $data);
    }
}
