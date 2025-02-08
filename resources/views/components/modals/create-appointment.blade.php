@extends('components.modal')
@section('modal-body')
<form id="formCreateAppointment" action="{{ route('appointment.store', ['id' => $b_id, 'title' => $b_title]) }}" method="POST">
    @csrf
    <div class="row" style="gap: 10px 0px;">
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label">Họ tên</label>
                <input id="customer_name" maxlength="50" name="customer_name" class="form-control" type="text" placeholder="Nhập họ tên">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label">Số điện thoại/Zalo</label>
                <input id="phone" name="phone" class="form-control" maxlength="10" type="text" placeholder="Nhập số điện thoại/Zalo" inputmode="numeric">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label">Số người ở</label>
                <input id="total_person" name="total_person" class="form-control" type="number" placeholder="Nhập số người ở" inputmode="numeric" value="1" min="0">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label">Số xe</label>
                <input id="total_bike" name="total_bike" class="form-control" type="number" placeholder="Nhập số xe" inputmode="numeric" value="0" min="0">
            </div>
        </div>
        <div class="form-group">
            <label class="form-control-label">Ngày chuyển vào (Không bắt buộc)</label>
            <input class="form-control" type="date" value="" id="move_in_date" name="move_in_date" placeholder="dd/mm/YYYY">
        </div>
        <div class="form-group">
            <label class="form-control-label">Ngày hẹn xem</label>
            <input class="form-control" type="datetime-local" value="" id="appointment_at" name="appointment_at" placeholder="dd/mm/YYYY HH:ii">
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="form-control-label">Ghi chú</label>
                <textarea id="note" maxlength="255" name="note" class="form-control" placeholder="Nhập ghi chú"></textarea>
            </div>
        </div>
    </div>
</form>
{{-- <div class="confirmation-appointment">
</div> --}}
@stop