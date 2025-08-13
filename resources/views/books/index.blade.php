@extends('layouts.app')

@section('title', 'Books')

@push('styles')
<link href="{{ asset('css/books.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="books-container">
    <div id="alertContainer"></div>
    
    <div class="books-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2><i class="fas fa-book"></i> Books</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" id="addBookBtn">
                    <i class="fas fa-plus"></i> Add Book
                </button>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-12">
                <form id="searchForm">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="titleSearch" name="title_search" 
                                   placeholder="Search by title..." 
                                   value="{{ request('title_search') }}">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="authorSearch" name="author_search" 
                                   placeholder="Search by author..." 
                                   value="{{ request('author_search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" type="button" id="clearSearch">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="books-table-container" id="booksTableContainer">
        @include('books.partials.table')
    </div>
</div>

<!-- Book Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalTitle">Add Book</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bookForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" id="bookId" name="id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="publicationDate" class="form-label">Publication Date *</label>
                                <input type="date" class="form-control" id="publicationDate" name="publication_date" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="authors" class="form-label">Authors *</label>
                                <div class="authors-select-container">
                                    <div id="selectedAuthors" class="selected-authors">
                                        <span class="text-muted">No authors selected</span>
                                    </div>
                                    <select class="form-select" id="authors" name="authors[]" multiple required>
                                        <!-- Options will be loaded dynamically -->
                                    </select>
                                </div>
                                <div class="form-text">Click authors above to select/deselect them</div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="image" class="form-label">Book Cover</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png">
                                <div class="form-text">JPG or PNG, max 2MB</div>
                            </div>
                            
                            <div class="text-center">
                                <img id="imagePreview" class="image-preview" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBookBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Common -->
<script src="{{ asset('js/common/utils.js') }}"></script>

<!-- Books AJAX -->
<script src="{{ asset('js/books/ajax/books-load.js') }}"></script>
<script src="{{ asset('js/books/ajax/books-create.js') }}"></script>
<script src="{{ asset('js/books/ajax/books-update.js') }}"></script>
<script src="{{ asset('js/books/ajax/books-delete.js') }}"></script>
<script src="{{ asset('js/books/ajax/books-edit.js') }}"></script>
<script src="{{ asset('js/books/ajax/authors-load.js') }}"></script>

<!-- Books UI -->
<script src="{{ asset('js/books/ui/search.js') }}"></script>
<script src="{{ asset('js/books/ui/authors-select.js') }}"></script>
<script src="{{ asset('js/books/ui/image-preview.js') }}"></script>
<script src="{{ asset('js/books/ui/modal.js') }}"></script>

<!-- Main Books File -->
<script src="{{ asset('js/books.js') }}"></script>
@endpush
