/**
 * Load author for editing
 */
function loadAuthorForEdit(authorId) {
    return $.get(`/authors/${authorId}/edit`);
}
