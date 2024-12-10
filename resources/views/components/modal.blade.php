<div class="modal fade" id="{{ $id }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-{{ $size ?? 'md' }}" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}Label">{{ $title ?? '' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Đóng</button>
        @if(!($readyOnly ?? false))
        <button id="btn-submit" type="button" class="btn bg-gradient-primary">{{ $okText ?? 'Lưu' }}</button>
        @endif
      </div>
    </div>
  </div>
</div>