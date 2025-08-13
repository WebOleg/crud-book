<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
   use RefreshDatabase;

   /**
    * Test book has fillable attributes
    */
   public function test_book_has_fillable_attributes(): void
   {
       $book = new Book();
       
       $fillable = ['title', 'description', 'image_path', 'publication_date'];
       
       $this->assertEquals($fillable, $book->getFillable());
   }

   /**
    * Test book has correct casts
    */
   public function test_book_has_correct_casts(): void
   {
       $book = new Book();
       
       $this->assertEquals('date', $book->getCasts()['publication_date']);
   }

   /**
    * Test book can be created
    */
   public function test_book_can_be_created(): void
   {
       $bookData = [
           'title' => 'Test Book',
           'description' => 'Test Description',
           'publication_date' => '2023-01-01',
       ];

       $book = Book::create($bookData);

       $this->assertInstanceOf(Book::class, $book);
       $this->assertEquals('Test Book', $book->title);
       $this->assertEquals('Test Description', $book->description);
       $this->assertEquals('2023-01-01', $book->publication_date->format('Y-m-d'));
   }

   /**
    * Test book authors relationship
    */
   public function test_book_has_authors_relationship(): void
   {
       $book = Book::factory()->create();
       $author = Author::factory()->create();
       
       $book->authors()->attach($author);

       $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $book->authors);
       $this->assertTrue($book->authors->contains($author));
   }

   /**
    * Test book can have multiple authors
    */
   public function test_book_can_have_multiple_authors(): void
   {
       $book = Book::factory()->create();
       $authors = Author::factory()->count(3)->create();
       
       $book->authors()->attach($authors);

       $this->assertEquals(3, $book->authors->count());
   }

   /**
    * Test book publication date is cast to Carbon
    */
   public function test_book_publication_date_is_cast_to_carbon(): void
   {
       $book = Book::factory()->create([
           'publication_date' => '2023-01-01'
       ]);

       $this->assertInstanceOf(\Carbon\Carbon::class, $book->publication_date);
   }
}
