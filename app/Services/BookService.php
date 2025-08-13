<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BookService
{
    /**
     * Create a new book with authors and optional image
     */
    public function createBook(array $data, ?UploadedFile $image = null): Book
    {
        return DB::transaction(function () use ($data, $image) {
            if ($image) {
                $data['image_path'] = $this->uploadImage($image);
            }

            $book = Book::create($data);
            $book->authors()->attach($data['authors']);

            return $book->load('authors');
        });
    }

    /**
     * Update existing book
     */
    public function updateBook(Book $book, array $data, ?UploadedFile $image = null): Book
    {
        return DB::transaction(function () use ($book, $data, $image) {
            if ($image) {
                // Delete old image if exists
                if ($book->image_path) {
                    Storage::disk('public')->delete($book->image_path);
                }
                $data['image_path'] = $this->uploadImage($image);
            }

            $book->update($data);
            $book->authors()->sync($data['authors']);

            return $book->load('authors');
        });
    }

    /**
     * Delete book with cleanup
     */
    public function deleteBook(Book $book): bool
    {
        return DB::transaction(function () use ($book) {
            if ($book->image_path) {
                Storage::disk('public')->delete($book->image_path);
            }

            return $book->delete();
        });
    }

    /**
     * Upload and store book image with unique name
     */
    private function uploadImage(UploadedFile $file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('books', $filename, 'public');
    }

    /**
     * Search books with advanced filtering
     */
    public function searchBooks(array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Book::with('authors');

        if (!empty($filters['title_search'])) {
            $query->where('title', 'like', "%{$filters['title_search']}%");
        }

        if (!empty($filters['author_search'])) {
            $authorSearch = $filters['author_search'];
            $query->whereHas('authors', function($authorQuery) use ($authorSearch) {
                $authorQuery->where(function($q) use ($authorSearch) {
                    $q->where('last_name', 'like', "%{$authorSearch}%")
                      ->orWhere('first_name', 'like', "%{$authorSearch}%")
                      ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$authorSearch}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$authorSearch}%"]);
                });
            });
        }

        if (!empty($filters['year'])) {
            $query->whereYear('publication_date', $filters['year']);
        }

        $sortBy = $filters['sort'] ?? 'title';
        $sortDirection = $filters['direction'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate(15);
    }
}
