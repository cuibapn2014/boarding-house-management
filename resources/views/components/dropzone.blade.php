<div id="{{ $id ?? '' }}" class="dropzone border rounded position-relative p-2 {{ $class ?? '' }}" style="min-height: 10rem;border-style:dashed !important;">
    <p class="text-center m-0 py-2 text-sm position-absolute w-100 none-file" style="top:50%;left:0;transform:translateY(-50%);opacity:0.6;user-select:none;">Kéo thả tệp hoặc nhấp vào đây</p>
    <div class="dropzone-preview-files d-flex flex-wrap mb-4" style="gap: 5px 5px;max-width:fit-content"></div>
    <input class="dropzone-input-file d-none" type="file"/>
</div>