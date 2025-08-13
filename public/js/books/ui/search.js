/**
 * Restore search values from hidden inputs
 */
function restoreBooksSearchValues() {
    const titleSearch = $('#currentTitleSearch').val();
    const authorSearch = $('#currentAuthorSearch').val();
    
    if (titleSearch) {
        $('#titleSearch').val(titleSearch);
    }
    if (authorSearch) {
        $('#authorSearch').val(authorSearch);
    }
}

/**
 * Get current search parameters for books
 */
function getCurrentBooksParams() {
    const params = new URLSearchParams();
    
    const titleSearch = $('#titleSearch').val();
    const authorSearch = $('#authorSearch').val();
    
    if (titleSearch) params.append('title_search', titleSearch);
    if (authorSearch) params.append('author_search', authorSearch);
    
    return params;
}
