<?php

namespace Tests\Feature\Author;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authors index page loads successfully
     */
    public function test_authors_index_page_loads(): void
    {
        $response = $this->get('/authors');

        $response->assertStatus(200);
        $response->assertViewIs('authors.index');
    }

    /**
     * Test authors are displayed on index page
     */
    public function test_authors_are_displayed_on_index(): void
    {
        $authors = Author::factory()->count(3)->create();

        $response = $this->get('/authors');

        $response->assertStatus(200);
        foreach ($authors as $author) {
            $response->assertSee($author->last_name);
            $response->assertSee($author->first_name);
        }
    }

    /**
     * Test authors pagination works
     */
    public function test_authors_pagination_works(): void
    {
        Author::factory()->count(20)->create();

        $response = $this->get('/authors');

        $response->assertStatus(200);
        $response->assertViewHas('authors');
        $this->assertEquals(15, $response->viewData('authors')->perPage());
    }

    /**
     * Test authors search by last name
     */
    public function test_authors_search_by_last_name(): void
    {
        $author1 = Author::factory()->create(['last_name' => 'Smith']);
        $author2 = Author::factory()->create(['last_name' => 'Johnson']);

        $response = $this->get('/authors?search=Smith');

        $response->assertStatus(200);
        $response->assertSee($author1->last_name);
        $response->assertDontSee($author2->last_name);
    }

    /**
     * Test authors search by first name
     */
    public function test_authors_search_by_first_name(): void
    {
        $author1 = Author::factory()->create(['first_name' => 'UniqueJohn', 'last_name' => 'Smith']);
        $author2 = Author::factory()->create(['first_name' => 'UniqueJane', 'last_name' => 'Doe']);

        $response = $this->get('/authors?search=UniqueJohn');

        $response->assertStatus(200);
        $response->assertSee($author1->first_name);
        $response->assertDontSee($author2->first_name);
    }

    /**
     * Test authors sorting by last name ascending
     */
    public function test_authors_sort_by_last_name_asc(): void
    {
        Author::factory()->create(['last_name' => 'Zebra']);
        Author::factory()->create(['last_name' => 'Alpha']);

        $response = $this->get('/authors?sort=last_name&direction=asc');

        $response->assertStatus(200);
        $authors = $response->viewData('authors');
        $this->assertEquals('Alpha', $authors->first()->last_name);
    }

    /**
     * Test authors sorting by last name descending
     */
    public function test_authors_sort_by_last_name_desc(): void
    {
        Author::factory()->create(['last_name' => 'Alpha']);
        Author::factory()->create(['last_name' => 'Zebra']);

        $response = $this->get('/authors?sort=last_name&direction=desc');

        $response->assertStatus(200);
        $authors = $response->viewData('authors');
        $this->assertEquals('Zebra', $authors->first()->last_name);
    }

    /**
     * Test AJAX request returns partial view
     */
    public function test_ajax_request_returns_partial_view(): void
    {
        Author::factory()->count(3)->create();

        $response = $this->ajaxGet('/authors');

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
