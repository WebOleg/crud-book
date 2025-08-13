/**
 * Restore search values from hidden inputs
 */
function restoreAuthorsSearchValues() {
    const search = $('#currentSearch').val();
    if (search) {
        $('#search').val(search);
    }
}

/**
 * Get current search parameters for authors
 */
function getCurrentAuthorsParams() {
    const params = new URLSearchParams();
    
    const search = $('#search').val();
    if (search) params.append('search', search);
    
    return params;
}
