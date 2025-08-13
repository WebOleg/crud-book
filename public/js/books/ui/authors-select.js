/**
 * Update selected authors display
 */
function updateSelectedAuthors() {
    const selected = $('#authors').val() || [];
    const container = $('#selectedAuthors');
    
    if (selected.length === 0) {
        container.html('<span class="text-muted">No authors selected</span>');
        return;
    }

    let html = '';
    selected.forEach(authorId => {
        const option = $(`#authors option[value="${authorId}"]`);
        const authorName = option.text();
        html += `
            <span class="selected-author-tag">
                ${authorName}
                <span class="remove-author" data-id="${authorId}">×</span>
            </span>
        `;
    });
    
    container.html(html);
}

/**
 * Remove author from selection
 */
function removeAuthor(authorId) {
    const select = $('#authors');
    const currentValues = select.val() || [];
    const newValues = currentValues.filter(id => id !== authorId.toString());
    select.val(newValues);
    updateSelectedAuthors();
}
