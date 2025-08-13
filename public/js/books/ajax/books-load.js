/**
 * Load books page via AJAX
 */
function loadBooksPage(url) {
    return $.get(url)
        .done((data) => {
            $('#booksTableContainer').html(data);
            restoreBooksSearchValues();
        })
        .fail(() => {
            showAlert('Error loading data', 'danger');
        });
}
