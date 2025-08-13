class BooksManager {
    constructor() {
        this.init();
    }

    /**
     * Initialize all functionality
     */
    init() {
        this.bindEvents();
        this.setupPagination();
        setupImagePreview();
        restoreBooksSearchValues();
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
        $('#titleSearch, #authorSearch').on('input', debounce(() => {
            this.performSearch();
        }, 500));

        // Clear search
        $('#clearSearch').on('click', () => {
            $('#titleSearch, #authorSearch').val('');
            this.performSearch();
        });

        // Sort functionality
        $(document).on('click', '.sortable', (e) => {
            e.preventDefault();
            this.sortTable(e.currentTarget);
        });

        // Add book modal
        $('#addBookBtn').on('click', () => {
            showAddBookModal();
        });

        // Save book
        $('#saveBookBtn').on('click', () => {
            this.saveBook();
        });

        // Edit book buttons (delegated)
        $(document).on('click', '.edit-book', (e) => {
            const bookId = $(e.currentTarget).data('id');
            showEditBookModal(bookId);
        });

        // Delete book buttons (delegated)
        $(document).on('click', '.delete-book', (e) => {
            const bookId = $(e.currentTarget).data('id');
            this.deleteBookAction(bookId);
        });

        // Authors selection
        $(document).on('change', '#authors', () => {
            updateSelectedAuthors();
        });

        // Remove author tag
        $(document).on('click', '.remove-author', (e) => {
            const authorId = $(e.currentTarget).data('id');
            removeAuthor(authorId);
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
                loadBooksPage(url);
            }
        });
    }

    /**
     * Perform search with current form data
     */
    performSearch() {
        const params = getCurrentBooksParams();
        
        // Add current sort
        const currentSort = $('#currentSort').val();
        const currentDirection = $('#currentDirection').val();
        
        if (currentSort) params.append('sort', currentSort);
        if (currentDirection) params.append('direction', currentDirection);
        
        const url = '/books?' + params.toString();
        loadBooksPage(url);
    }

    /**
     * Sort table by column
     */
    sortTable(element) {
        const column = $(element).data('sort');
        const direction = $(element).data('direction');
        
        const params = getCurrentBooksParams();
        params.append('sort', column);
        params.append('direction', direction);
        
        const url = '/books?' + params.toString();
        loadBooksPage(url);
    }

    /**
     * Save book (create or update)
     */
    saveBook() {
        const bookId = $('#bookId').val();
        const formData = new FormData($('#bookForm')[0]);
        
        const promise = bookId ? updateBook(bookId, formData) : createBook(formData);
        
        promise
            .done((response) => {
                $('#bookModal').modal('hide');
                showAlert(response.message, 'success');
                this.performSearch();
            })
            .fail(handleValidationErrors);
    }

    /**
     * Delete book action
     */
    deleteBookAction(bookId) {
        if (confirm('Are you sure you want to delete this book?')) {
            deleteBook(bookId)
                .done((response) => {
                    showAlert(response.message, 'success');
                    this.performSearch();
                })
                .fail(() => {
                    showAlert('Error deleting book', 'danger');
                });
        }
    }
}

// Initialize when document is ready
$(document).ready(() => {
    new BooksManager();
});
