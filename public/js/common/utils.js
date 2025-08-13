/**
 * Debounce function to limit API calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Convert snake_case to camelCase
 */
function camelCase(str) {
    return str.replace(/_([a-z])/g, (match, letter) => letter.toUpperCase());
}

/**
 * Show alert message
 */
function showAlert(message, type, containerId = 'alertContainer') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $(`#${containerId}`).html(alertHtml);
    
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

/**
 * Handle validation errors
 */
function handleValidationErrors(xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON.errors;
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        for (const field in errors) {
            let inputId = camelCase(field);
            if (field === 'publication_date') {
                inputId = 'publicationDate';
            }
            
            const input = $(`#${inputId}`);
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
        }
    } else {
        showAlert('Error saving data', 'danger');
    }
}
