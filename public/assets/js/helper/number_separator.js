$(document).on('input', '.number-separator', function(e) {
    const value = Number($(this).val().replace(/\D/g, ''));

    if(Number.isNaN(value)) {
        $(this).val(0);
        return;
    }

    $(this).val(value.toLocaleString('vi'));
})

