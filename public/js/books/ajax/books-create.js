/**
 * Create new book
 */
function createBook(formData) {
    return $.ajax({
        url: '/books',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}
