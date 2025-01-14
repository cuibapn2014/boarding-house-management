<div id="loading">
    <div class="loading__content d-flex flex-column align-items-center">
        <img class="rounded skeleton" src="{{ asset('assets/images/icon/logo.png') }}" alt="Logo" loading="lazy" decoding="async"/>
        <div class="d-flex mt-3 align-items-center">
            <div class="spinner-border mx-2 text-secondary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span>Đang tải</span>
        </div>
    </div>
    <div class="overlay"></div>
</div>