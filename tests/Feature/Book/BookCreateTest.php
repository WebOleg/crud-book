<?php

namespace Tests\Feature\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookCreateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /**
     * Test book can be created with valid data
     */
    public function test_book_can_be_created_with_valid_data(): void
    {
        $author = Author::factory()->create();
        
        $bookData = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Book created successfully',
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'description' => 'Test Description',
        ]);

        $book = Book::where('title', 'Test Book')->first();
        $this->assertNotNull($book);
        $this->assertEquals('2023-01-01', $book->publication_date->format('Y-m-d'));
        $this->assertTrue($book->authors->contains($author));
    }

    /**
     * Test book can be created with image
     */
    public function test_book_can_be_created_with_image(): void
    {
        $author = Author::factory()->create();
        $image = UploadedFile::fake()->image('book.jpg', 100, 100);
        
        $bookData = [
            'title' => 'Test Book with Image',
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
            'image' => $image,
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(200);
        
        $book = Book::where('title', 'Test Book with Image')->first();
        $this->assertNotNull($book->image_path);
        Storage::disk('public')->assertExists($book->image_path);
    }

    /**
     * Test book creation fails without title
     */
    public function test_book_creation_fails_without_title(): void
    {
        $author = Author::factory()->create();
        
        $bookData = [
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title']);
    }

    /**
     * Test book creation fails without publication date
     */
    public function test_book_creation_fails_without_publication_date(): void
    {
        $author = Author::factory()->create();
        
        $bookData = [
            'title' => 'Test Book',
            'authors' => [$author->id],
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['publication_date']);
    }

    /**
     * Test book creation fails without authors
     */
    public function test_book_creation_fails_without_authors(): void
    {
        $bookData = [
            'title' => 'Test Book',
            'publication_date' => '2023-01-01',
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['authors']);
    }

    /**
     * Test book creation fails with invalid image format
     */
    public function test_book_creation_fails_with_invalid_image_format(): void
    {
        $author = Author::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 1000);
        
        $bookData = [
            'title' => 'Test Book',
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
            'image' => $file,
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    /**
     * Test book creation fails with oversized image
     */
    public function test_book_creation_fails_with_oversized_image(): void
    {
        $author = Author::factory()->create();
        $image = UploadedFile::fake()->image('book.jpg')->size(3000); // 3MB
        
        $bookData = [
            'title' => 'Test Book',
            'publication_date' => '2023-01-01',
            'authors' => [$author->id],
            'image' => $image,
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }

    /**
     * Test book creation with multiple authors
     */
    public function test_book_creation_with_multiple_authors(): void
    {
        $authors = Author::factory()->count(3)->create();
        
        $bookData = [
            'title' => 'Multi Author Book',
            'publication_date' => '2023-01-01',
            'authors' => $authors->pluck('id')->toArray(),
        ];

        $response = $this->withHeaders(['Accept' => 'application/json'])
                         ->post('/books', $bookData);

        $response->assertStatus(200);
        
        $book = Book::where('title', 'Multi Author Book')->first();
        $this->assertEquals(3, $book->authors->count());
    }
}
