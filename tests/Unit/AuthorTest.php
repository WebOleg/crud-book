<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test author has fillable attributes
     */
    public function test_author_has_fillable_attributes(): void
    {
        $author = new Author();
        
        $fillable = ['last_name', 'first_name', 'middle_name'];
        
        $this->assertEquals($fillable, $author->getFillable());
    }

    /**
     * Test author can be created
     */
    public function test_author_can_be_created(): void
    {
        $authorData = [
            'last_name' => 'Doe',
            'first_name' => 'John',
            'middle_name' => 'William',
        ];

        $author = Author::create($authorData);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertEquals('Doe', $author->last_name);
        $this->assertEquals('John', $author->first_name);
        $this->assertEquals('William', $author->middle_name);
    }

    /**
     * Test author books relationship
     */
    public function test_author_has_books_relationship(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create();
        
        $author->books()->attach($book);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $author->books);
        $this->assertTrue($author->books->contains($book));
    }

    /**
     * Test author full name attribute
     */
    public function test_author_full_name_attribute(): void
    {
        $author = Author::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'middle_name' => 'William',
        ]);

        $this->assertEquals('Doe John William', $author->full_name);
    }

    /**
     * Test author full name without middle name
     */
    public function test_author_full_name_without_middle_name(): void
    {
        $author = Author::factory()->create([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'middle_name' => null,
        ]);

        $this->assertEquals('Doe John', $author->full_name);
    }

    /**
     * Test author can have multiple books
     */
    public function test_author_can_have_multiple_books(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create();
        
        $author->books()->attach($books);

        $this->assertEquals(3, $author->books->count());
    }
}
