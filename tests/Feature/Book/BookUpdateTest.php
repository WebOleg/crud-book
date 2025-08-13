<?php

namespace Tests\Feature\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test book can be loaded for editing
     */
    public function test_book_can_be_loaded_for_editing(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $response = $this->get("/books/{$book->id}/edit");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'book' => [
                'id',
                'title',
                'description',
                'publication_date',
                'authors'
            ],
            'authors'
        ]);
        
        $this->assertEquals($book->id, $response->json('book.id'));
        $this->assertEquals($book->title, $response->json('book.title'));
    }

    /**
     * Test book can be updated with valid data
     */
    public function test_book_can_be_updated_with_valid_data(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);
        
        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'publication_date' => '2024-01-01',
            'authors' => [$author->id],
        ];

        $response = $this->ajaxPut("/books/{$book->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Book updated successfully',
        ]);

        $book->refresh();
        $this->assertEquals('Updated Title', $book->title);
        $this->assertEquals('Updated Description', $book->description);
        $this->assertEquals('2024-01-01', $book->publication_date->format('Y-m-d'));
    }

    /**
     * Test book image can be updated
     */
    public function test_book_image_can_be_updated(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['image_path' => 'old/path.jpg']);
        $book->authors()->attach($author);
        
        $newImage = UploadedFile::fake()->image('new-book.jpg');
        
        $updateData = [
            'title' => $book->title,
            'publication_date' => $book->publication_date->format('Y-m-d'),
            'authors' => [$author->id],
            'image' => $newImage,
        ];

        $response = $this->ajaxPut("/books/{$book->id}", $updateData);

        $response->assertStatus(200);
        
        $book->refresh();
        $this->assertNotEquals('old/path.jpg', $book->image_path);
        Storage::disk('public')->assertExists($book->image_path);
    }

    /**
     * Test book update fails with invalid data
     */
    public function test_book_update_fails_with_invalid_data(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);
        
        $updateData = [
            'title' => '', // Empty title
            'publication_date' => 'invalid-date',
            'authors' => [], // No authors
        ];

        $response = $this->ajaxPut("/books/{$book->id}", $updateData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'publication_date', 'authors']);
    }

    /**
     * Test book authors can be updated
     */
    public function test_book_authors_can_be_updated(): void
    {
        $oldAuthor = Author::factory()->create();
        $newAuthor = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($oldAuthor);
        
        $updateData = [
            'title' => $book->title,
            'publication_date' => $book->publication_date->format('Y-m-d'),
            'authors' => [$newAuthor->id],
        ];

        $response = $this->ajaxPut("/books/{$book->id}", $updateData);

        $response->assertStatus(200);
        
        $book->refresh();
        $this->assertFalse($book->authors->contains($oldAuthor));
        $this->assertTrue($book->authors->contains($newAuthor));
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
