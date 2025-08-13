<div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr>
                <th width="80">Cover</th>
                <th>
                    <a href="#" class="text-white text-decoration-none sortable" 
                       data-sort="title" 
                       data-direction="{{ request('sort') == 'title' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                        Title
                        @if(request('sort') == 'title')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
                <th>Authors</th>
                <th>Description</th>
                <th>
                    <a href="#" class="text-white text-decoration-none sortable" 
                       data-sort="publication_date" 
                       data-direction="{{ request('sort') == 'publication_date' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                        Publication Date
                        @if(request('sort') == 'publication_date')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
                <tr>
                    <td>
                        @if($book->image_path)
                            <img src="{{ Storage::url($book->image_path) }}" 
                                 alt="{{ $book->title }}" 
                                 class="book-image">
                        @else
                            <div class="book-image bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-book text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="book-title">{{ $book->title }}</div>
                    </td>
                    <td>
                        <div class="book-authors">
                            @foreach($book->authors as $author)
                                <span class="badge bg-secondary me-1">{{ $author->full_name }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="book-description" title="{{ $book->description }}">
                            {{ $book->description ?? '-' }}
                        </div>
                    </td>
                    <td>{{ $book->publication_date->format('Y-m-d') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary edit-book" 
                                data-id="{{ $book->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-book" 
                                data-id="{{ $book->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-book fa-2x mb-2"></i><br>
                        No books found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($books->hasPages())
    <div class="p-3 border-top">
        {{ $books->appends(request()->query())->links('pagination.bootstrap') }}
    </div>
@endif

<!-- Hidden inputs to store current filter values -->
<input type="hidden" id="currentTitleSearch" value="{{ request('title_search') }}">
<input type="hidden" id="currentAuthorSearch" value="{{ request('author_search') }}">
<input type="hidden" id="currentSort" value="{{ request('sort') }}">
<input type="hidden" id="currentDirection" value="{{ request('direction') }}">
