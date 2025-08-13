/**
 * Load authors page via AJAX
 */
function loadAuthorsPage(url) {
    return $.get(url)
        .done((data) => {
            $('#authorsTableContainer').html(data);
            restoreAuthorsSearchValues();
        })
        .fail(() => {
            showAlert('Error loading data', 'danger');
        });
}
