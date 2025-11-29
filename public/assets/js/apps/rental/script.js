var delaySearch;

$(document).ready(function() {
    Rental.initalFilter();

    $(document).on('click', '#pagination a.page-link', function(e) {
        e.preventDefault();

        Rental.loadData($(this));
    });

    $(document).on('change', 'input[name="price[]"], input[name="category[]"], input[name="district[]"], input[name="furniture_status[]"]', function(e) {
        clearTimeout(delaySearch);

        delaySearch = setTimeout(() => Rental.filter($(this)), 550);
    });

    $('.offcanvas .form-search input[type="checkbox"]').each(function(key, item) {
        $(this).attr('id', $(this).attr('id').concat('_mobile'));
        $(this).closest('label').attr('for', $(this).attr('id'));
    });

    $(document).on('click', '.reset-filter', function(e) {
        e.preventDefault();
        const formSearch = $(this).closest('.form-search');
        const input = formSearch.find('input[type="checkbox"]');

        input.each(function(key, item) {
            $(this).prop('checked', false);
        });

        input.first().trigger('change');
    });
});