<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuthorService
{
    /**
     * Get all authors with caching
     */
    public function getAllAuthors(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('authors.all', 3600, function () {
            return Author::orderBy('last_name')->get();
        });
    }

    /**
     * Search authors with advanced filtering
     */
    public function searchAuthors(array $filters): LengthAwarePaginator
    {
        $query = Author::withCount('books');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort'] ?? 'last_name';
        $sortDirection = $filters['direction'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate(15);
    }

    /**
     * Clear authors cache
     */
    public function clearCache(): void
    {
        Cache::forget('authors.all');
    }

    /**
     * Create author and clear cache
     */
    public function createAuthor(array $data): Author
    {
        $author = Author::create($data);
        $this->clearCache();
        return $author;
    }

    /**
     * Update author and clear cache
     */
    public function updateAuthor(Author $author, array $data): Author
    {
        $author->update($data);
        $this->clearCache();
        return $author;
    }

    /**
     * Delete author and clear cache
     */
    public function deleteAuthor(Author $author): bool
    {
        $result = $author->delete();
        $this->clearCache();
        return $result;
    }
}
