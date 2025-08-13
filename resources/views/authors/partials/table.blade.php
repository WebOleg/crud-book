<div class="table-responsive">
    <table class="table table-striped table-hover mb-0">
        <thead>
            <tr>
                <th>
                    <a href="#" class="text-white text-decoration-none sortable" 
                       data-sort="last_name" 
                       data-direction="{{ request('sort') == 'last_name' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                        Last Name
                        @if(request('sort') == 'last_name')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="#" class="text-white text-decoration-none sortable" 
                       data-sort="first_name" 
                       data-direction="{{ request('sort') == 'first_name' && request('direction') == 'asc' ? 'desc' : 'asc' }}">
                        First Name
                        @if(request('sort') == 'first_name')
                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </a>
                </th>
                <th>Middle Name</th>
                <th>Books Count</th>
                <th width="120">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authors as $author)
                <tr>
                    <td>{{ $author->last_name }}</td>
                    <td>{{ $author->first_name }}</td>
                    <td>{{ $author->middle_name ?? '-' }}</td>
                    <td>
                        <span class="badge bg-secondary">{{ $author->books_count ?? 0 }}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary edit-author" 
                                data-id="{{ $author->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-author" 
                                data-id="{{ $author->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-users fa-2x mb-2"></i><br>
                        No authors found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($authors->hasPages())
    <div class="p-3 border-top">
        {{ $authors->appends(request()->query())->links('pagination.bootstrap') }}
    </div>
@endif

<!-- Hidden inputs to store current filter values -->
<input type="hidden" id="currentSearch" value="{{ request('search') }}">
<input type="hidden" id="currentSort" value="{{ request('sort') }}">
<input type="hidden" id="currentDirection" value="{{ request('direction') }}">
