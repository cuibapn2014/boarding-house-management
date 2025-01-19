const BoardingHouse = {
    
    loadData: function(ele = null, _url = null) {
        let url = window.location.href;

        if(ele) url = ele.attr('href');

        if(_url) url = _url; 

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
            GlobalHelper.initTinyEditor(`#${modalCreate.attr('id')} #content`);
            BoardingHouse.initSelectDistrict(`#${modalCreate.attr('id')}`);

            modalCreate.modal('show');
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    store: async function(e) {
        e.preventDefault();

        const modal = $('#createBoardingHouseModal');
        const form = $('#formCreateBoardingHouse');
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        const _token = $('meta[name="csrf_token"]').attr('content');

        $('.input-error-message').remove();

        formData.set('content', tinymce.get('content').getContent());

        const handleSuccess = function(response) {
            if(response.status === 'error') {
                GlobalHelper.toastError(response.message);
                return;
            }

            GlobalHelper.toastSuccess(response.message);
            modal.modal('hide');
            BoardingHouse.loadData();
            Dropzone.files = [];
        }
        
        Dropzone.files.forEach(item => {
            formData.append('files[]', item);
        })

        await ApiHelper
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
            () => modal.find('#btn-submit').prop('disabled', true), 
            handleSuccess,
            null,
            () => modal.find('#btn-submit').prop('disabled', false)
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
            GlobalHelper.initTinyEditor(`#${modalEdit.attr('id')} #content`);
            BoardingHouse.initSelectDistrict(`#${modalEdit.attr('id')}`);

            modalEdit.modal('show');
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    showModalClone: function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const modalCreate = $('#createBoardingHouseModal');

        const handleSuccess = function(response) {
            modalCreate.find('.modal-body').html(response);
            GlobalHelper.initTinyEditor(`#${modalCreate.attr('id')} #content`);
            BoardingHouse.initSelectDistrict(`#${modalCreate.attr('id')}`);

            modalCreate.modal('show');
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

        formData.set('content', tinymce.get('content').getContent());

        const handleSuccess = function(response) {
            if(response.status === 'error') {
                GlobalHelper.toastError(response.message);
                return;
            }

            GlobalHelper.toastSuccess(response.message);
            modal.modal('hide');
            BoardingHouse.loadData();
            Dropzone.files = [];
        }

        Dropzone.files.forEach(item => {
            formData.append('files[]', item);
        })

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
            () => modal.find('#btn-submit').prop('disabled', true), 
            handleSuccess,
            null,
            () => modal.find('#btn-submit').prop('disabled', false)
        )
        .then(() => {})
        .catch(err => {});
    },

    removeFile: function(ele) {
        const url = ele.data('url');
        const _token = $('meta[name="csrf_token"]').attr('content');
        
        const handleSuccess = function(response) {
            if(response.status == 'success') {
                GlobalHelper.toastSuccess(response.message);
                ele.closest('.file-uploaded').remove();

                return;
            }
            
            GlobalHelper.toastError(response.message);
        }

        ApiHelper
        .callApi(url, 
            'DELETE', 
            {}, 
            {"X-CSRF-TOKEN" : _token}, 
            {}, 
            null, 
            handleSuccess
        )
        .then(() => {})
        .catch(err => {});
    },

    destroy: function(ele) {
        const url = ele.data('url');
        const _token = $('meta[name="csrf_token"]').attr('content');
        const modalConfirm = $('#confirmDeleteBoardingHouseModal');

        const handleSuccess = function(response) {
            if(response.status == 'success') {
                GlobalHelper.toastSuccess(response.message);
                BoardingHouse.loadData();
                modalConfirm.modal('hide');
                return;
            }

            GlobalHelper.toastError(response.message);
        }

        ApiHelper
        .callApi(url, 
            'DELETE', 
            {}, 
            {"X-CSRF-TOKEN" : _token}, 
            {}, 
            () => modalConfirm.find('#btn-confirm-delete').prop('disabled', true), 
            handleSuccess,
            null,
            () => modalConfirm.find('#btn-confirm-delete').prop('disabled', false)
        )
        .then(() => {})
        .catch(err => {});
    },

    setLocationHCM: function() {
        if(localStorage.getItem('location_hcm')) {
            locationHCM = JSON.parse(localStorage.getItem('location_hcm'));
            return;
        }

        const handleSuccess = function(response) {
            locationHCM = response.find(item => item.codename === 'thanh_pho_ho_chi_minh');
            localStorage.setItem('location_hcm', JSON.stringify(locationHCM));
        }

        ApiHelper
        .callApi(apiProvince, 
            'GET', 
            {}, 
            {}, 
            {}, 
            null, 
            handleSuccess
        )
        .then(() => {})
        .catch(err => {});
    },

    initSelectDistrict: function(selectorModal) {
        selectorModal = selectorModal ? selectorModal : '.modal.show';
        const district = $(selectorModal).find('#district');
        const ward = $(selectorModal).find('#ward');
        const districtSelected = district.val();
        const wardSelected = ward.val();

        district.empty();
        district.append(`
            <option value="">Chọn quận/huyện</option>
        `);
        locationHCM.districts.forEach(item => {
            district.append(`
                <option value="${item.name}" ${item.name === districtSelected ? 'selected' : ''}>${item.name}</option>
            `);
        });

        ward.empty();
        ward.append(`
            <option value="">Chọn phường/xã</option>
        `);
        if(districtSelected) {
            const districts = locationHCM.districts.find(item => item.name === districtSelected);

            districts?.wards?.forEach(item => {
                ward.append(`
                    <option value="${item.name}" ${item.name === wardSelected ? 'selected' : ''}>${item.name}</option>
                `);
            });
        }
    },

    handleSelectDistrict: function(e) {
        const selected = $('.modal.show #district').val();
        const districts = locationHCM.districts.find(item => item.name === selected);
        const ward = $('.modal #ward');

        ward.empty();
        districts?.wards?.forEach(item => {
            ward.append(`
                <option value="${item.name}">${item.name}</option>
            `);
        });
    },

    showModalCreateAppointment: function(e) {
        e.preventDefault();

        const url = $(this).data('url');
        const modalCreate = $('#createAppointmentModal');

        const handleSuccess = function(response) {
            modalCreate.find('.modal-body').html(response);
            flatpickr('#createAppointmentModal #move_in_date', {
                enableTime: false,
                dateFormat: "d/m/Y",
                locale: 'vn',
                disableMobile: true,
                time_24h: true,
                allowInput: true
            });

            flatpickr('#createAppointmentModal #appointment_at', {
                enableTime: true,
                dateFormat: "d/m/Y H:i",
                locale: 'vn',
                disableMobile: true,
                time_24h: true,
                allowInput: true
            });

            modalCreate.modal('show');
        }

        ApiHelper
            .callApi(url, 'GET', {}, {}, {}, null, handleSuccess)
            .then(() => {})
            .catch(err => console.log(err));
    },

    storeAppointment: async function(e) {
        e.preventDefault();

        const modal = $('#createAppointmentModal');
        const form = $('#formCreateAppointment');
        const formData = new FormData(form[0]);
        const url = form.attr('action');
        const method = form.attr('method');
        const _token = $('meta[name="csrf_token"]').attr('content');

        $('.input-error-message').remove();

        const handleSuccess = function(response) {
            if(response.status === 'error') {
                GlobalHelper.toastError(response.message);
                return;
            }

            GlobalHelper.toastSuccess(response.message);
            modal.modal('hide');
            // BoardingHouse.loadData();
        }
        
        await ApiHelper
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
            () => modal.find('#btn-submit').prop('disabled', true), 
            handleSuccess,
            null,
            () => modal.find('#btn-submit').prop('disabled', false)
        )
        .then(() => {})
        .catch(err => {});
    },
}