@extends('master')
@section('title', 'Chính sách bảo mật')
@push('css')
<style>
    .container-custom {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .container-custom h1, .container-custom h2 {
        color: #4CAF50 !important;
        font-weight: 550;
    }
    .container-custom ul {
        margin-left: 20px !important;
    }

    .container-custom h1 {
        font-size: 2em !important;
    }

    .container-custom h2 {
        font-size: 1.5em !important;
    }
</style>
@endpush
@section('content')
<div class="container-custom">
    <h1>Chính Sách Bảo Mật</h1>
    <p>Chúng tôi cam kết bảo vệ sự riêng tư và an toàn thông tin cá nhân của bạn. Dưới đây là các chính sách về việc thu thập, sử dụng và bảo vệ thông tin cá nhân.</p>

    <h2>1. Thu Thập Thông Tin</h2>
    <p>Chúng tôi thu thập các thông tin sau khi bạn sử dụng dịch vụ:</p>
    <ul>
        <li>Thông tin cá nhân: họ tên, địa chỉ email, số điện thoại.</li>
        <li>Thông tin địa chỉ IP, thời gian truy cập, lịch sử duyệt.</li>
        <li>Thông tin thanh toán (nếu có).</li>
    </ul>

    <h2>2. Sử Dụng Thông Tin</h2>
    <p>Chúng tôi sử dụng thông tin của bạn để:</p>
    <ul>
        <li>Cung cấp dịch vụ và hỗ trợ khách hàng.</li>
        <li>Nâng cao chất lượng sản phẩm và dịch vụ.</li>
        <li>Gửi các thông báo, khuyến mãi hoặc cập nhật quan trọng.</li>
    </ul>

    <h2>3. Bảo Vệ Thông Tin</h2>
    <p>Chúng tôi áp dụng các biện pháp an ninh nhằm bảo vệ thông tin cá nhân:</p>
    <ul>
        <li>Mã hóa dữ liệu khi truyền tải.</li>
        <li>Giới hạn quyền truy cập dữ liệu.</li>
        <li>Kiểm tra bảo mật định kỳ.</li>
    </ul>

    <h2>4. Quyền Của Bạn</h2>
    <p>Bạn có quyền:</p>
    <ul>
        <li>Yêu cầu xem, chỉnh sửa hoặc xóa thông tin cá nhân.</li>
        <li>Huỷ đăng ký nhận email từ chúng tôi.</li>
        <li>Gửi khiếu nại về việc sử dụng thông tin.</li>
    </ul>

    <h2>5. Liên Hệ</h2>
    <p>Nếu bạn có bất kỳ thắc mắc hoặc quan ngại nào, vui lòng liên hệ chúng tôi qua:</p>
    <ul>
        <li>Email: nmtworks.7250@gmail.com</li>
        <li>Phone/Zalo: 0388 794 195</li>
    </ul>
</div>
@endsection