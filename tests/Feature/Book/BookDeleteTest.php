<?php

namespace Tests\Feature\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test book can be deleted
     */
    public function test_book_can_be_deleted(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $response = $this->ajaxDelete("/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Book deleted successfully',
        ]);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * Test deleting book removes author relationships
     */
    public function test_deleting_book_removes_author_relationships(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $this->assertDatabaseHas('author_book', [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ]);

        $response = $this->ajaxDelete("/books/{$book->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('author_book', [
            'author_id' => $author->id,
            'book_id' => $book->id,
        ]);
    }

    /**
     * Test deleting book removes image file
     */
    public function test_deleting_book_removes_image_file(): void
    {
        $author = Author::factory()->create();
        $image = UploadedFile::fake()->image('book.jpg');
        
        // Create book with image
        $bookData = [
            'title' => 'Test Book',
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
            'image' => $image,
        ];

        $this->ajaxPost('/books', $bookData);
        $book = Book::where('title', 'Test Book')->first();
        
        // Verify image exists
        Storage::disk('public')->assertExists($book->image_path);

        // Delete book
        $response = $this->ajaxDelete("/books/{$book->id}");

        $response->assertStatus(200);
        // Verify image was removed
        Storage::disk('public')->assertMissing($book->image_path);
    }

    /**
     * Test deleting non-existent book returns 404
     */
    public function test_deleting_non_existent_book_returns_404(): void
    {
        $response = $this->ajaxDelete('/books/999');

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
