/**
 * Delete author
 */
function deleteAuthor(authorId) {
    return $.ajax({
        url: `/authors/${authorId}`,
        method: 'DELETE'
    });
}
