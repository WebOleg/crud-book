<?php

namespace Tests\Feature\Author;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test author can be deleted
     */
    public function test_author_can_be_deleted(): void
    {
        $author = Author::factory()->create();

        $response = $this->ajaxDelete("/authors/{$author->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Author deleted successfully',
        ]);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }

    /**
     * Test deleting author also removes book relationships
     */
    public function test_deleting_author_removes_book_relationships(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $this->assertDatabaseHas('author_book', [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ]);

        $response = $this->ajaxDelete("/authors/{$author->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('author_book', [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Test deleting non-existent author returns 404
     */
    public function test_deleting_non_existent_author_returns_404(): void
    {
        $response = $this->ajaxDelete('/authors/999');

        $response->assertStatus(404);
    }

    /**
     * Helper method for AJAX DELETE requests
     */
    private function ajaxDelete(string $uri): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->delete($uri);
    }
}
