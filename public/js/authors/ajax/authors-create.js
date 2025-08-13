/**
 * Create new author
 */
function createAuthor(formData) {
    return $.ajax({
        url: '/authors',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}
