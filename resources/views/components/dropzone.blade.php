<div id="{{ $id ?? '' }}" class="dropzone border rounded position-relative p-2 {{ $class ?? '' }}" style="min-height: 10rem;border-style:dashed !important;">
    <p class="text-center m-0 py-2 text-sm position-absolute w-100 none-file" style="top:50%;left:0;transform:translateY(-50%);opacity:0.6;user-select:none;">
        <i class="fas fa-cloud-upload-alt fa-2x mb-2 d-block" style="opacity: 0.3;margin:auto"></i>
        <span class="d-block">Kéo thả ảnh/video vào đây</span>
        <small class="text-muted d-block mt-1">hoặc nhấp để chọn file</small>
    </p>
    <div class="dropzone-preview-files d-flex flex-wrap mb-4" style="gap: 8px 8px;max-width:fit-content"></div>
    <input class="dropzone-input-file d-none" type="file" multiple accept="image/png,image/jpeg,image/jpg,image/webp,video/mp4"/>
</div>