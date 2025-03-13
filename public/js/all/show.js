$(document).ready(function () {
    $('tbody tr').on('click', function () {
        const url = $(this).data('href');
        if (url) {
            window.location.href = url; 
        }
    });
});