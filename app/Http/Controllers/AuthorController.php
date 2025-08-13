<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors with search and sorting
     */
    public function index(Request $request)
    {
        $query = Author::withCount('books');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('last_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort', 'last_name');
        $sortDirection = $request->get('direction', 'asc');
        
        $query->orderBy($sortBy, $sortDirection);

        $authors = $query->paginate(15);

        if ($request->ajax()) {
            return view('authors.partials.table', compact('authors'))->render();
        }

        return view('authors.index', compact('authors'));
    }

    /**
     * Get all authors for API calls
     */
    public function getAuthors()
    {
        $authors = Author::orderBy('last_name')->get();
        return response()->json($authors);
    }

    /**
     * Store a newly created author
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = Author::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Author created successfully',
            'author' => $author
        ]);
    }

    /**
     * Show the form for editing the specified author
     */
    public function edit(Author $author)
    {
        return response()->json($author);
    }

    /**
     * Update the specified author
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Author updated successfully',
            'author' => $author
        ]);
    }

    /**
     * Remove the specified author
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author deleted successfully'
        ]);
    }
}
