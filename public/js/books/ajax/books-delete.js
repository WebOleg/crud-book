/**
 * Delete book
 */
function deleteBook(bookId) {
    return $.ajax({
        url: `/books/${bookId}`,
        method: 'DELETE'
    });
}
