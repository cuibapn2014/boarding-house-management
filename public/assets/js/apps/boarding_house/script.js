"use strict";
const apiProvince = 'https://provinces.open-api.vn/api/?depth=3';
var locationHCM;
var delaySearch;

$(document).ready(function() {
    initShowAdvanceFilter();
    GlobalHelper.initValueSearchForm();
    BoardingHouse.setLocationHCM();

    // Search functionality
    $(document).on('search input cut paste', '#byTitle, #byFromPrice, #byToPrice', function(e) {
        clearTimeout(delaySearch);
        delaySearch = setTimeout(() => search($(this)), 550);
    })

    $(document).on('change', '#byCategory, #byStatus, #byPublish', function(e) {
        clearTimeout(delaySearch);
        delaySearch = setTimeout(() => search($(this)), 550);
    })

    // Delete boarding house
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

    // Advance filter toggle
    $(document).on('click', '#btn-advance-filter', function() {
        handleClickAdvanceFilter();
        $(this).find('.toggle-icon').toggleClass('rotate');
    });

    // Handle collapse events for smooth animation
    $('#advance-filter').on('show.bs.collapse', function() {
        $('#btn-advance-filter').find('.toggle-icon').addClass('rotate');
    });

    $('#advance-filter').on('hide.bs.collapse', function() {
        $('#btn-advance-filter').find('.toggle-icon').removeClass('rotate');
    });

    // Create appointment modal
    $(document).on('click', '.create-appointment', BoardingHouse.showModalCreateAppointment);
    $(document).on('click', '#createAppointmentModal #btn-submit', BoardingHouse.storeAppointment);

    // Clone boarding house
    $(document).on('click', '.clone-boarding-house', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        window.location.href = url;
    });

    // Quick filter badges
    $(document).on('click', '.filter-badge', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        const formSearch = $('#form-search__boarding-house');
        
        // Remove active class from all badges
        $('.filter-badge').removeClass('active');
        
        // Add active class to clicked badge
        $(this).addClass('active');
        
        if(filter === 'all') {
            formSearch.find('#byStatus').val('');
        } else if(filter === 'available') {
            formSearch.find('#byStatus').val('available');
        } else if(filter === 'rented') {
            formSearch.find('#byStatus').val('rented');
        }
        
        // Trigger search
        formSearch.find('#byStatus').trigger('change');
        
        // Update filter count
        updateFilterCount();
    });

    // Clear filter button
    $(document).on('click', '#btn-clear-filter', function(e) {
        e.preventDefault();
        clearAllFilters();
    });

    // Update filter count on any filter change
    $(document).on('change', '#byCategory, #byStatus, #byPublish', function() {
        updateFilterCount();
    });

    $(document).on('input', '#byFromPrice, #byToPrice', function() {
        updateFilterCount();
    });

    // Initialize filter count on page load
    updateFilterCount();
});

function search(ele) {
    const formSearch = ele.closest('#form-search__boarding-house');
    const serialize = formSearch.serialize();
    const url = `${window.location.origin}${window.location.pathname}?${serialize}`;

    window.history.pushState(null, {}, url);

    BoardingHouse.loadData(null, url);
}

function handleClickAdvanceFilter() {
    const advanceFilter = $('#advance-filter');
    const isExpanded = advanceFilter.hasClass('show');
    
    // Save state to localStorage
    localStorage.setItem('boarding_house.__advance_filter', !isExpanded);
}

function initShowAdvanceFilter() {
    const isAdvanceFilter = localStorage.getItem('boarding_house.__advance_filter') ?? 'false';

    if(JSON.parse(isAdvanceFilter)) {
        $('#advance-filter').addClass('show');
        $('#btn-advance-filter').find('.toggle-icon').addClass('rotate');
    }
}

function updateFilterCount() {
    const formSearch = $('#form-search__boarding-house');
    let count = 0;

    // Count active filters
    if(formSearch.find('#byTitle').val().trim() !== '') count++;
    if(formSearch.find('#byCategory').val() !== '') count++;
    if(formSearch.find('#byFromPrice').val().trim() !== '') count++;
    if(formSearch.find('#byToPrice').val().trim() !== '') count++;
    if(formSearch.find('#byPublish').val() !== '') count++;
    
    // Update filter count badge
    const filterCountBadge = $('#filter-count');
    if(count > 0) {
        filterCountBadge.text(count).removeClass('d-none');
        $('#btn-clear-filter').addClass('show');
    } else {
        filterCountBadge.addClass('d-none');
        $('#btn-clear-filter').removeClass('show');
    }
}

function clearAllFilters() {
    const formSearch = $('#form-search__boarding-house');
    
    // Clear all form fields
    formSearch.find('#byTitle').val('');
    formSearch.find('#byCategory').val('');
    formSearch.find('#byFromPrice').val('');
    formSearch.find('#byToPrice').val('');
    formSearch.find('#byStatus').val('');
    formSearch.find('#byPublish').val('');
    
    // Reset quick filter badges to "Tất cả"
    $('.filter-badge').removeClass('active');
    $('.filter-badge[data-filter="all"]').addClass('active');
    
    // Update filter count
    updateFilterCount();
    
    // Trigger search to refresh data
    const url = `${window.location.origin}${window.location.pathname}`;
    window.history.pushState(null, {}, url);
    BoardingHouse.loadData(null, url);
    
    // Show success message
    GlobalHelper.toastSuccess('Đã xóa tất cả bộ lọc');
}