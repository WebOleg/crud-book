/**
 * Show add book modal
 */
function showAddBookModal() {
    $('#bookForm')[0].reset();
    $('#bookId').val('');
    $('#imagePreview').hide();
    $('#selectedAuthors').html('<span class="text-muted">No authors selected</span>');
    $('#bookModalTitle').text('Add Book');
    
    loadAuthorsForBooks()
        .done((authors) => {
            const authorsSelect = $('#authors');
            authorsSelect.empty();
            
            authors.forEach(author => {
                const fullName = `${author.last_name} ${author.first_name}`;
                authorsSelect.append(new Option(fullName, author.id));
            });
            
            $('#bookModal').modal('show');
        })
        .fail(() => {
            showAlert('Error loading authors', 'danger');
        });
}

/**
 * Show edit book modal
 */
function showEditBookModal(bookId) {
    loadBookForEdit(bookId)
        .done((data) => {
            const book = data.book;
            const authors = data.authors;
            
            $('#bookId').val(book.id);
            $('#title').val(book.title);
            $('#description').val(book.description);
            $('#publicationDate').val(book.publication_date);
            
            // Setup authors dropdown
            const authorsSelect = $('#authors');
            authorsSelect.empty();
            
            authors.forEach(author => {
                const fullName = `${author.last_name} ${author.first_name}`;
                const option = new Option(fullName, author.id);
                authorsSelect.append(option);
            });
            
            // Select current authors
            const selectedAuthors = book.authors.map(author => author.id.toString());
            authorsSelect.val(selectedAuthors);
            updateSelectedAuthors();
            
            // Show current image if exists
            if (book.image_path) {
                $('#imagePreview').attr('src', `/storage/${book.image_path}`).show();
            } else {
                $('#imagePreview').hide();
            }
            
            $('#bookModalTitle').text('Edit Book');
            $('#bookModal').modal('show');
        })
        .fail(() => {
            showAlert('Error loading book data', 'danger');
        });
}
