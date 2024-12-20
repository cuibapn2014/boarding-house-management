"use strict";
const apiProvince = 'https://provinces.open-api.vn/api/?depth=3';
var locationHCM;

$(document).ready(function() {
    BoardingHouse.setLocationHCM();

    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        
        BoardingHouse.loadData($(this));
    });

    $(document).on('click', '#btn-create-boarding-house', BoardingHouse.showModalCreate);
    $(document).on('click', '.edit-boarding-house', BoardingHouse.showModalEdit);

    $(document).on('click', '#createBoardingHouseModal #btn-submit', BoardingHouse.store);
    $(document).on('click', '#editBoardingHouseModal #btn-submit', BoardingHouse.update);

    $(document).on('shown.bs.modal', '#createBoardingHouseModal, #editBoardingHouseModal', function() {
        Dropzone.destroy('#' + $(this).attr('id') + ' .dropzone');
        Dropzone.init('#' + $(this).attr('id') + ' .dropzone');
    });

    $(document).on('show.bs.modal', '#createBoardingHouseModal, #editBoardingHouseModal', function() {
        new Tagify(document.querySelector('#' + $(this).attr('id') + ' #tags'));
    });

    $(document).on('hide.bs.modal', '#createBoardingHouseModal, #editBoardingHouseModal', function() {
        if (tinymce.get('content')) {
            tinymce.remove('#content');
        }
    });

    $(document).on('click', '.remove-file', function(e) {
        e.preventDefault();

        BoardingHouse.removeFile($(this));
    });

    $(document).on('click', '.remove-boarding-house', function(e) {
        e.preventDefault();

        const modalConfirm = $('#confirmDeleteBoardingHouseModal');
        modalConfirm.find('#btn-confirm-delete').attr('data-url', $(this).data('url'));
        modalConfirm.find('.modal-body').text('Bạn có chắn chắn muốn xoá dữ liệu này?');
        modalConfirm.modal('show');
    });

    $(document).on('click', '#confirmDeleteBoardingHouseModal #btn-confirm-delete', function(e) {
        e.preventDefault();

        BoardingHouse.destroy($(this));
    });

    $(document).on('change', '#createBoardingHouseModal #district, #editBoardingHouseModal #district', BoardingHouse.handleSelectDistrict);
});