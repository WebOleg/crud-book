<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Kobzar',
                'description' => 'Collection of poems by Taras Shevchenko',
                'publication_date' => '1840-01-01',
                'authors' => [1] // Shevchenko
            ],
            [
                'title' => 'Zakhar Berkut',
                'description' => 'Historical novel about Carpathian highlanders',
                'publication_date' => '1883-01-01',
                'authors' => [2] // Franko
            ],
            [
                'title' => 'Forest Song',
                'description' => 'Drama-fairy tale in verse',
                'publication_date' => '1911-01-01',
                'authors' => [3] // Lesia Ukrainka
            ],
            [
                'title' => 'Aeneid',
                'description' => 'Burlesque poem, parody of Virgils Aeneid',
                'publication_date' => '1798-01-01',
                'authors' => [4] // Kotlyarevsky
            ],
            [
                'title' => 'Winter Trees',
                'description' => 'Collection of poems',
                'publication_date' => '1970-01-01',
                'authors' => [5] // Stus
            ],
            [
                'title' => 'Ukraine in Flames',
                'description' => 'Autobiographical story about WWII',
                'publication_date' => '1943-01-01',
                'authors' => [6] // Dovzhenko
            ],
            [
                'title' => 'The Cathedral',
                'description' => 'Novel about Ukrainian history',
                'publication_date' => '1968-01-01',
                'authors' => [7] // Honchar
            ],
            [
                'title' => 'Songs',
                'description' => 'Collection of lyrical poems',
                'publication_date' => '1929-01-01',
                'authors' => [8] // Rylsky
            ],
            [
                'title' => 'Love Ukraine',
                'description' => 'Patriotic poem',
                'publication_date' => '1944-01-01',
                'authors' => [9] // Sosyura
            ],
            [
                'title' => 'Solar Clarinets',
                'description' => 'Collection of poems',
                'publication_date' => '1918-01-01',
                'authors' => [10] // Tychyna
            ],
            [
                'title' => 'Tiger Trappers',
                'description' => 'Adventure novel',
                'publication_date' => '1944-01-01',
                'authors' => [11] // Bagryany
            ],
            [
                'title' => 'Equilibrium',
                'description' => 'Philosophical novel',
                'publication_date' => '1918-01-01',
                'authors' => [12] // Vynnychenko
            ],
            [
                'title' => 'The Stone Cross',
                'description' => 'Novel about emigration',
                'publication_date' => '1900-01-01',
                'authors' => [13] // Stefanyk
            ],
            [
                'title' => 'Shadows of Forgotten Ancestors',
                'description' => 'Novel about Hutsul life',
                'publication_date' => '1911-01-01',
                'authors' => [14] // Kotsyubynsky
            ],
            [
                'title' => 'My Native Land',
                'description' => 'Collection of poems about homeland',
                'publication_date' => '1964-01-01',
                'authors' => [15] // Malyshko
            ],
            [
                'title' => 'Roxolana',
                'description' => 'Historical novel',
                'publication_date' => '1980-01-01',
                'authors' => [16] // Zahrebelnyi
            ],
            [
                'title' => 'The City',
                'description' => 'Novel about urban life',
                'publication_date' => '1928-01-01',
                'authors' => [17] // Pidmohylny
            ],
            [
                'title' => 'I Am (Romance)',
                'description' => 'Modernist novel',
                'publication_date' => '1924-01-01',
                'authors' => [18] // Khvylyovy
            ],
            [
                'title' => 'The Builders of Towers',
                'description' => 'Collection of poems',
                'publication_date' => '1929-01-01',
                'authors' => [19] // Bazhan
            ],
            [
                'title' => 'Death of the Squadron',
                'description' => 'Novel about Civil War',
                'publication_date' => '1926-01-01',
                'authors' => [20] // Antonenko-Davydovych
            ],
            [
                'title' => 'Ukrainian Literature Anthology',
                'description' => 'Collection of works by multiple authors',
                'publication_date' => '1950-01-01',
                'authors' => [1, 2, 3] // Multiple authors
            ],
            [
                'title' => 'Modern Ukrainian Poetry',
                'description' => 'Collection of contemporary poems',
                'publication_date' => '1975-01-01',
                'authors' => [5, 8, 10] // Multiple authors
            ],
            [
                'title' => 'Historical Novels Collection',
                'description' => 'Best historical works',
                'publication_date' => '1985-01-01',
                'authors' => [2, 7, 16] // Multiple authors
            ],
            [
                'title' => 'Drama Masterpieces',
                'description' => 'Collection of Ukrainian dramas',
                'publication_date' => '1960-01-01',
                'authors' => [3, 12] // Multiple authors
            ],
            [
                'title' => 'Patriotic Songs',
                'description' => 'Collection of patriotic poetry',
                'publication_date' => '1991-01-01',
                'authors' => [1, 9, 15] // Multiple authors
            ]
        ];

        foreach ($books as $bookData) {
            $authors = $bookData['authors'];
            unset($bookData['authors']);
            
            $book = Book::create($bookData);
            $book->authors()->attach($authors);
        }
    }
}
