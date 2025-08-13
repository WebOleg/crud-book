/**
 * Update existing book
 */
function updateBook(bookId, formData) {
    formData.append('_method', 'PUT');
    
    return $.ajax({
        url: `/books/${bookId}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}
