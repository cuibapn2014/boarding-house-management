const BoardingHouse = {
    loadData: function(ele = null) {
        let url = window.location.href;

        if(ele) url = ele.attr('href');

        window.history.pushState({}, null, url);

        const handleSuccess = function(response) {
            $('.card-body.list-data').replaceWith($(response).find('.card-body.list-data'));
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    showModalCreate: function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const modalCreate = $('#createBoardingHouseModal');

        const handleSuccess = function(response) {
            modalCreate.find('.modal-body').html(response);
            modalCreate.modal('show');
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    store: function(e) {
        e.preventDefault();

        const modal = $('#createBoardingHouseModal');
        const form = $('#formCreateBoardingHouse');
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        const _token = $('meta[name="csrf_token"]').attr('content');

        $('.input-error-message').remove();

        const handleSuccess = function(response) {
            GlobalHelper.toastSuccess(response.message);
            modal.modal('hide');
            BoardingHouse.loadData();
        }

        ApiHelper
        .callApi(url, 
            method, 
            formData, 
            {
                "X-CSRF-TOKEN" : _token
            }, 
            {
                processData: false,
                contentType: false,
            }, 
            null, 
            handleSuccess
        )
        .then(() => {})
        .catch(err => {});
    },

    showModalEdit: function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const modalEdit = $('#editBoardingHouseModal');

        const handleSuccess = function(response) {
            modalEdit.find('.modal-body').html(response);
            modalEdit.modal('show');
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    update: function(e) {
        e.preventDefault();

        const modal = $('#editBoardingHouseModal');
        const form = $('#formEditBoardingHouse');
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        const _token = $('meta[name="csrf_token"]').attr('content');

        $('.input-error-message').remove();

        const handleSuccess = function(response) {
            GlobalHelper.toastSuccess(response.message);
            modal.modal('hide');
            BoardingHouse.loadData();
        }

        ApiHelper
        .callApi(url, 
            method, 
            formData, 
            {
                "X-CSRF-TOKEN" : _token
            }, 
            {
                processData: false,
                contentType: false,
            }, 
            null, 
            handleSuccess
        )
        .then(() => {})
        .catch(err => {});
    },
}