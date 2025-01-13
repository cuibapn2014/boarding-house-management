const Rental = {
    loadData: function(ele = null, _url = null) {
        let url = window.location.href;

        if(ele) url = ele.attr('href');

        if(_url) url = _url; 

        window.history.pushState({}, null, url);

        const handleSuccess = function(response) {
            $('.list-home .container').replaceWith($(response).find('.list-home .container'));
            $(window).scrollTop(336);
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    filter: function(ele) {
        const formSearch = $(ele).closest('.form-search');
        const serialize = formSearch.serialize();
        const url = `${window.location.origin}${window.location.pathname}?${serialize}`;

        window.history.pushState({}, null, url);

        const handleSuccess = function(response) {
            $('.list-home .container').replaceWith($(response).find('.list-home .container'));
            $(window).scrollTop(336);
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    initalFilter: function() {
        const search = new URLSearchParams(window.location.search);

        search.forEach((value, key) => {
            key = key.replace(/\[\d+\]/g, '[]');
            $(`input[name="${key}"][value="${value}"]`).each(function(item) {
                $(this).prop('checked', true);
            });
        })
    }
}