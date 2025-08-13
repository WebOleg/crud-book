/**
 * Load book for editing
 */
function loadBookForEdit(bookId) {
    return $.get(`/books/${bookId}/edit`);
}
