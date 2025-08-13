/**
 * Load authors list for books
 */
function loadAuthorsForBooks() {
    return $.get('/api/authors');
}
