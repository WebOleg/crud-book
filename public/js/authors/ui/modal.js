/**
 * Show add author modal
 */
function showAddAuthorModal() {
    $('#authorForm')[0].reset();
    $('#authorId').val('');
    $('#authorModalTitle').text('Add Author');
    $('#authorModal').modal('show');
}

/**
 * Show edit author modal
 */
function showEditAuthorModal(authorId) {
    loadAuthorForEdit(authorId)
        .done((author) => {
            $('#authorId').val(author.id);
            $('#lastName').val(author.last_name);
            $('#firstName').val(author.first_name);
            $('#middleName').val(author.middle_name);
            $('#authorModalTitle').text('Edit Author');
            $('#authorModal').modal('show');
        })
        .fail(() => {
            showAlert('Error loading author data', 'danger');
        });
}
