"use strict";

$(document).ready(function() {
    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        
        BoardingHouse.loadData($(this));
    });

    $(document).on('click', '#btn-create-boarding-house', BoardingHouse.showModalCreate);
    $(document).on('click', '.edit-boarding-house', BoardingHouse.showModalEdit);

    $(document).on('click', '#createBoardingHouseModal #btn-submit', BoardingHouse.store);
    $(document).on('click', '#editBoardingHouseModal #btn-submit', BoardingHouse.update);
});