const BOARDING_HOUSE_MAX_PRICE_STR = '999999999999999';

$(document).on('input', '.number-separator', function (e) {
    let digits = $(this).val().replace(/\D/g, '');

    if (digits === '') {
        $(this).val(0);
        return;
    }

    if (
        digits.length > BOARDING_HOUSE_MAX_PRICE_STR.length
        || (digits.length === BOARDING_HOUSE_MAX_PRICE_STR.length && digits > BOARDING_HOUSE_MAX_PRICE_STR)
    ) {
        digits = BOARDING_HOUSE_MAX_PRICE_STR;
    }

    const value = Number(digits);

    if (Number.isNaN(value)) {
        $(this).val(0);
        return;
    }

    $(this).val(value.toLocaleString('vi'));
});
