/**
 * Update existing author
 */
function updateAuthor(authorId, formData) {
    formData.append('_method', 'PUT');
    
    return $.ajax({
        url: `/authors/${authorId}`,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false
    });
}
