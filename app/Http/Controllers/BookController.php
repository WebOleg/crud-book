<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use App\Services\AuthorService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        private BookService $bookService,
        private AuthorService $authorService
    ) {}

    /**
     * Display a listing of books with search and sorting
     */
    public function index(Request $request)
    {
        $filters = $request->only(['title_search', 'author_search', 'year', 'sort', 'direction']);
        $books = $this->bookService->searchBooks($filters);

        if ($request->ajax()) {
            return view('books.partials.table', compact('books'))->render();
        }

        return view('books.index', compact('books'));
    }

    /**
     * Store a newly created book
     */
    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->createBook(
            $request->validated(),
            $request->file('image')
        );

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'book' => new BookResource($book)
        ]);
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        $book->load('authors');
        $authors = $this->authorService->getAllAuthors();
        
        return response()->json([
            'book' => new BookResource($book),
            'authors' => $authors->map(fn($author) => [
                'id' => $author->id,
                'name' => $author->full_name
            ])
        ]);
    }

    /**
     * Update the specified book
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = $this->bookService->updateBook(
            $book,
            $request->validated(),
            $request->file('image')
        );

        return response()->json([
            'success' => true,
            'message' => 'Book updated successfully',
            'book' => new BookResource($book)
        ]);
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);

        return response()->json([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }
}
