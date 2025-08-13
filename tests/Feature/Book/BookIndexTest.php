<?php

namespace Tests\Feature\Book;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test books index page loads successfully
     */
    public function test_books_index_page_loads(): void
    {
        $response = $this->get('/books');

        $response->assertStatus(200);
        $response->assertViewIs('books.index');
    }

    /**
     * Test books are displayed on index page
     */
    public function test_books_are_displayed_on_index(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create();
        
        foreach ($books as $book) {
            $book->authors()->attach($author);
        }

        $response = $this->get('/books');

        $response->assertStatus(200);
        foreach ($books as $book) {
            $response->assertSee($book->title);
        }
    }

    /**
     * Test books search by title
     */
    public function test_books_search_by_title(): void
    {
        $author = Author::factory()->create();
        $book1 = Book::factory()->create(['title' => 'Laravel Guide']);
        $book2 = Book::factory()->create(['title' => 'PHP Basics']);
        
        $book1->authors()->attach($author);
        $book2->authors()->attach($author);

        $response = $this->get('/books?title_search=Laravel');

        $response->assertStatus(200);
        $response->assertSee($book1->title);
        $response->assertDontSee($book2->title);
    }

    /**
     * Test books search by author
     */
    public function test_books_search_by_author(): void
    {
        $author1 = Author::factory()->create(['last_name' => 'Smith']);
        $author2 = Author::factory()->create(['last_name' => 'Johnson']);
        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();
        
        $book1->authors()->attach($author1);
        $book2->authors()->attach($author2);

        $response = $this->get('/books?author_search=Smith');

        $response->assertStatus(200);
        $response->assertSee($book1->title);
        $response->assertDontSee($book2->title);
    }

    /**
     * Test books sorting by title
     */
    public function test_books_sort_by_title(): void
    {
        $author = Author::factory()->create();
        $book1 = Book::factory()->create(['title' => 'Zebra Book']);
        $book2 = Book::factory()->create(['title' => 'Alpha Book']);
        
        $book1->authors()->attach($author);
        $book2->authors()->attach($author);

        $response = $this->get('/books?sort=title&direction=asc');

        $response->assertStatus(200);
        $books = $response->viewData('books');
        $this->assertEquals('Alpha Book', $books->first()->title);
    }

    /**
     * Test books sorting by publication date
     */
    public function test_books_sort_by_publication_date(): void
    {
        $author = Author::factory()->create();
        $book1 = Book::factory()->create(['publication_date' => '2023-01-01']);
        $book2 = Book::factory()->create(['publication_date' => '2022-01-01']);
        
        $book1->authors()->attach($author);
        $book2->authors()->attach($author);

        $response = $this->get('/books?sort=publication_date&direction=desc');

        $response->assertStatus(200);
        $books = $response->viewData('books');
        $this->assertEquals('2023-01-01', $books->first()->publication_date->format('Y-m-d'));
    }

    /**
     * Test books pagination works
     */
    public function test_books_pagination_works(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(20)->create();
        
        foreach ($books as $book) {
            $book->authors()->attach($author);
        }

        $response = $this->get('/books');

        $response->assertStatus(200);
        $response->assertViewHas('books');
        $this->assertEquals(15, $response->viewData('books')->perPage());
    }

    /**
     * Test AJAX request returns partial view
     */
    public function test_ajax_request_returns_partial_view(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);

        $response = $this->ajaxGet('/books');

        $response->assertStatus(200);
        $this->assertStringContainsString('<table', $response->getContent());
        $this->assertStringNotContainsString('<!DOCTYPE', $response->getContent());
    }

    /**
     * Helper method for AJAX requests
     */
    private function ajaxGet(string $uri): \Illuminate\Testing\TestResponse
    {
        return $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
        ])->get($uri);
    }
}
