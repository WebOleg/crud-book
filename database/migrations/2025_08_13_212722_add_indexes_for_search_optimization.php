<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->index(['last_name', 'first_name'], 'authors_name_search_idx');
            $table->index('last_name', 'authors_last_name_idx');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->index('title', 'books_title_search_idx');
            $table->index('publication_date', 'books_publication_date_idx');
        });

        Schema::table('author_book', function (Blueprint $table) {
            $table->index(['author_id', 'book_id'], 'author_book_composite_idx');
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropIndex('authors_name_search_idx');
            $table->dropIndex('authors_last_name_idx');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('books_title_search_idx');
            $table->dropIndex('books_publication_date_idx');
        });

        Schema::table('author_book', function (Blueprint $table) {
            $table->dropIndex('author_book_composite_idx');
        });
    }
};
