"use strict";
const API_PROVINCES = 'https://provinces.open-api.vn/api/?depth=3';
var apiProvince = API_PROVINCES; // dùng chung với BoardingHouse.js
var locationHCM;
var searchDebounceTimer;

$(document).ready(function() {
    restoreAdvanceFilterFromStorage();
    GlobalHelper.initValueSearchForm();
    BoardingHouse.setLocationHCM();

    // Tìm kiếm: debounce 550ms
    $(document).on('search input cut paste', '#byTitle, #byFromPrice, #byToPrice', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => performListingSearch($(this)), 550);
    });
    $(document).on('change', '#byCategory, #byStatus, #byFurnitureStatus, #byPublish', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => performListingSearch($(this)), 550);
    });

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

    $(document).on('click', '#btn-advance-filter', function() {
        saveAdvanceFilterOpenState();
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

    // Clone listing: go to create page with source id
    $(document).on('click', '.clone-boarding-house', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        if (url) window.location.href = url;
    });

    // Admin: stop push listing
    $(document).on('click', '.stop-push-listing', function(e) {
        e.preventDefault();
        const btn = $(this);
        const url = btn.data('url');
        if (!url) return;
        if (!confirm('Bạn có chắc muốn dừng đẩy top tin này?')) return;
        const token = $('meta[name="csrf-token"]').attr('content') || $('meta[name="csrf_token"]').attr('content');
        $.ajax({
            url: url,
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            success: function(res) {
                if (res.status === 'success') {
                    GlobalHelper.toastSuccess(res.message);
                    BoardingHouse.loadData(null, window.location.href);
                } else {
                    GlobalHelper.toastError(res.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Không thể dừng đẩy top';
                GlobalHelper.toastError(msg);
            }
        });
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
        
        formSearch.find('#byStatus').trigger('change');
        refreshActiveFilterCount();
    });

    $(document).on('click', '#btn-clear-filter', function(e) {
        e.preventDefault();
        resetAllFiltersAndReload();
    });

    $(document).on('change', '#byCategory, #byStatus, #byFurnitureStatus, #byPublish', refreshActiveFilterCount);
    $(document).on('input', '#byFromPrice, #byToPrice', refreshActiveFilterCount);
    refreshActiveFilterCount();
});

/** Gửi form tìm kiếm và tải lại danh sách tin đăng */
function performListingSearch($triggerElement) {
    const $form = $triggerElement.closest('#form-search__boarding-house');
    const queryString = $form.serialize();
    const url = `${window.location.origin}${window.location.pathname}?${queryString}`;
    window.history.pushState(null, {}, url);
    BoardingHouse.loadData(null, url);
}

function saveAdvanceFilterOpenState() {
    const isOpen = $('#advance-filter').hasClass('show');
    localStorage.setItem('boarding_house.__advance_filter', !isOpen);
}

function restoreAdvanceFilterFromStorage() {
    const saved = localStorage.getItem('boarding_house.__advance_filter') ?? 'false';
    if (JSON.parse(saved)) {
        $('#advance-filter').addClass('show');
        $('#btn-advance-filter').find('.toggle-icon').addClass('rotate');
    }
}

/** Đếm số bộ lọc đang bật và cập nhật badge + nút xóa lọc */
function refreshActiveFilterCount() {
    const $form = $('#form-search__boarding-house');
    let activeCount = 0;
    if ($form.find('#byTitle').val().trim() !== '') activeCount++;
    if ($form.find('#byCategory').val() !== '') activeCount++;
    if ($form.find('#byFromPrice').val().trim() !== '') activeCount++;
    if ($form.find('#byToPrice').val().trim() !== '') activeCount++;
    if ($form.find('#byFurnitureStatus').val() !== '') activeCount++;
    if ($form.find('#byPublish').val() !== '') activeCount++;

    const $badge = $('#filter-count');
    if (activeCount > 0) {
        $badge.text(activeCount).removeClass('d-none');
        $('#btn-clear-filter').addClass('show');
    } else {
        $badge.addClass('d-none');
        $('#btn-clear-filter').removeClass('show');
    }
}

/** Xóa toàn bộ bộ lọc và tải lại danh sách */
function resetAllFiltersAndReload() {
    const $form = $('#form-search__boarding-house');
    $form.find('#byTitle').val('');
    $form.find('#byCategory').val('');
    $form.find('#byFromPrice').val('');
    $form.find('#byToPrice').val('');
    $form.find('#byStatus').val('');
    $form.find('#byFurnitureStatus').val('');
    $form.find('#byPublish').val('');
    $('.filter-badge').removeClass('active');
    $('.filter-badge[data-filter="all"]').addClass('active');
    refreshActiveFilterCount();
    const url = `${window.location.origin}${window.location.pathname}`;
    window.history.pushState(null, {}, url);
    BoardingHouse.loadData(null, url);
    GlobalHelper.toastSuccess('Đã xóa tất cả bộ lọc');
}