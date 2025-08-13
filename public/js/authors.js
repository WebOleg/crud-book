class AuthorsManager {
    constructor() {
        this.init();
    }

    /**
     * Initialize all event listeners
     */
    init() {
        this.bindEvents();
        this.setupPagination();
        restoreAuthorsSearchValues();
    }

    /**
     * Bind all event listeners
     */
    bindEvents() {
        // Search functionality
        $('#searchForm').on('submit', (e) => {
            e.preventDefault();
            this.performSearch();
        });

        // Real-time search
        $('#search').on('input', debounce(() => {
            this.performSearch();
        }, 500));

        // Clear search
        $('#clearSearch').on('click', () => {
            $('#search').val('');
            this.performSearch();
        });

        // Sort functionality
        $(document).on('click', '.sortable', (e) => {
            e.preventDefault();
            this.sortTable(e.currentTarget);
        });

        // Add author modal
        $('#addAuthorBtn').on('click', () => {
            showAddAuthorModal();
        });

        // Save author
        $('#saveAuthorBtn').on('click', () => {
            this.saveAuthor();
        });

        // Edit author buttons (delegated)
        $(document).on('click', '.edit-author', (e) => {
            const authorId = $(e.currentTarget).data('id');
            showEditAuthorModal(authorId);
        });

        // Delete author buttons (delegated)
        $(document).on('click', '.delete-author', (e) => {
            const authorId = $(e.currentTarget).data('id');
            this.deleteAuthorAction(authorId);
        });
    }

    /**
     * Setup pagination event listeners
     */
    setupPagination() {
        $(document).on('click', '.pagination a', (e) => {
            e.preventDefault();
            const url = $(e.currentTarget).attr('href');
            if (url) {
                loadAuthorsPage(url);
            }
        });
    }

    /**
     * Perform search with current form data
     */
    performSearch() {
        const params = getCurrentAuthorsParams();
        
        // Add current sort
        const currentSort = $('#currentSort').val();
        const currentDirection = $('#currentDirection').val();
        
        if (currentSort) params.append('sort', currentSort);
        if (currentDirection) params.append('direction', currentDirection);
        
        const url = '/authors?' + params.toString();
        loadAuthorsPage(url);
    }

    /**
     * Sort table by column
     */
    sortTable(element) {
        const column = $(element).data('sort');
        const direction = $(element).data('direction');
        
        const params = getCurrentAuthorsParams();
        params.append('sort', column);
        params.append('direction', direction);
        
        const url = '/authors?' + params.toString();
        loadAuthorsPage(url);
    }

    /**
     * Save author (create or update)
     */
    saveAuthor() {
        const authorId = $('#authorId').val();
        const formData = new FormData($('#authorForm')[0]);
        
        const promise = authorId ? updateAuthor(authorId, formData) : createAuthor(formData);
        
        promise
            .done((response) => {
                $('#authorModal').modal('hide');
                showAlert(response.message, 'success');
                this.performSearch();
            })
            .fail(handleValidationErrors);
    }

    /**
     * Delete author action
     */
    deleteAuthorAction(authorId) {
        if (confirm('Are you sure you want to delete this author?')) {
            deleteAuthor(authorId)
                .done((response) => {
                    showAlert(response.message, 'success');
                    this.performSearch();
                })
                .fail(() => {
                    showAlert('Error deleting author', 'danger');
                });
        }
    }
}

// Initialize when document is ready
$(document).ready(() => {
    new AuthorsManager();
});
