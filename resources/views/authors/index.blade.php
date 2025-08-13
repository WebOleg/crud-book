@extends('layouts.app')

@section('title', 'Authors')

@push('styles')
<link href="{{ asset('css/authors.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="authors-container">
   <div id="alertContainer"></div>
   
   <div class="authors-header">
       <div class="row align-items-center">
           <div class="col-md-6">
               <h2><i class="fas fa-users"></i> Authors</h2>
           </div>
           <div class="col-md-6 text-end">
               <button type="button" class="btn btn-primary" id="addAuthorBtn">
                   <i class="fas fa-plus"></i> Add Author
               </button>
           </div>
       </div>
       
       <div class="row mt-3">
           <div class="col-md-10">
               <form id="searchForm">
                   <div class="input-group">
                       <input type="text" class="form-control" id="search" name="search" 
                              placeholder="Search by last name or first name..." 
                              value="{{ request('search') }}">
                       <button class="btn btn-outline-secondary" type="submit">
                           <i class="fas fa-search"></i>
                       </button>
                   </div>
               </form>
           </div>
           <div class="col-md-2">
               <button class="btn btn-outline-secondary w-100" type="button" id="clearSearch">
                   <i class="fas fa-times"></i> Clear
               </button>
           </div>
       </div>
   </div>

   <div class="authors-table-container" id="authorsTableContainer">
       @include('authors.partials.table')
   </div>
</div>

<!-- Author Modal -->
<div class="modal fade" id="authorModal" tabindex="-1">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="authorModalTitle">Add Author</h5>
               <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
           </div>
           <form id="authorForm">
               <div class="modal-body">
                   <input type="hidden" id="authorId" name="id">
                   
                   <div class="mb-3">
                       <label for="lastName" class="form-label">Last Name *</label>
                       <input type="text" class="form-control" id="lastName" name="last_name" required>
                   </div>
                   
                   <div class="mb-3">
                       <label for="firstName" class="form-label">First Name *</label>
                       <input type="text" class="form-control" id="firstName" name="first_name" required>
                   </div>
                   
                   <div class="mb-3">
                       <label for="middleName" class="form-label">Middle Name</label>
                       <input type="text" class="form-control" id="middleName" name="middle_name">
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                   <button type="button" class="btn btn-primary" id="saveAuthorBtn">Save</button>
               </div>
           </form>
       </div>
   </div>
</div>
@endsection

@push('scripts')
<!-- Common -->
<script src="{{ asset('js/common/utils.js') }}"></script>

<!-- Authors AJAX -->
<script src="{{ asset('js/authors/ajax/authors-load.js') }}"></script>
<script src="{{ asset('js/authors/ajax/authors-create.js') }}"></script>
<script src="{{ asset('js/authors/ajax/authors-update.js') }}"></script>
<script src="{{ asset('js/authors/ajax/authors-delete.js') }}"></script>
<script src="{{ asset('js/authors/ajax/authors-edit.js') }}"></script>

<!-- Authors UI -->
<script src="{{ asset('js/authors/ui/search.js') }}"></script>
<script src="{{ asset('js/authors/ui/modal.js') }}"></script>

<!-- Main Authors File -->
<script src="{{ asset('js/authors.js') }}"></script>
@endpush
