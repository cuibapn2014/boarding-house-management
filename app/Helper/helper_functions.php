<?php

use Illuminate\Support\Str;
use SePay\Builders\CheckoutBuilder;
use SePay\SePayClient;

function numberRemoveComma(mixed $input)
{
    return str_replace(',', '.', str_replace('.', '', $input));
}

function numberFormatVi(int $input)
{
    return number_format($input, 0, ',', '.');
}

function convertDateWithFormat(?string $input, string $formatInput, string $formatOutput = 'Y-m-d')
{
    if (!$input || trim($input) === '') return null;

    $date = \Carbon\Carbon::createFromFormat($formatInput, $input);

    return $date->format($formatOutput);
}

function getLinkPreview($id, $title)
{
    $title = Str::slug($title);
    return "https://nhatrototsaigon.com/danh-sach-cho-thue/{$id}/{$title}";
}

/**
 * Trả về URL ảnh tối ưu cho thumbnail (Cloudinary: resize + auto format/quality).
 * Giảm tải và cải thiện performance hiển thị.
 */
function getOptimizedThumbnailUrl(?string $url, int $width = 400, int $height = 300): ?string
{
    if (! $url || trim($url) === '') {
        return null;
    }
    if (! str_contains($url, 'res.cloudinary.com')) {
        return $url;
    }
    // Cloudinary: chèn transformation sau /upload/ -> /upload/c_fill,w_400,h_300,f_auto,q_auto/
    $transform = "c_fill,w_{$width},h_{$height},f_auto,q_auto";
    if (preg_match('#(.*/upload/)(.*)#', $url, $m)) {
        return $m[1] . $transform . '/' . $m[2];
    }
    return $url;
}

function generatePaymentButton($amount, $description, $paymentCode = null)
{
    // Khởi tạo client
    $sepayMerchantId = env('SEPAY_MERCHANT_ID');
    $sepayMerchantSecret = env('SEPAY_SECRET');
    $env = env('SEPAY_ENV');
    $sepay = new SePayClient($sepayMerchantId, $sepayMerchantSecret, $env);

    // Sử dụng payment_code nếu có, nếu không thì tạo mã tự động
    $invoiceNumber = $paymentCode ? $paymentCode : ('INV-' . time());

    // Tạo dữ liệu đơn hàng
    $checkoutData = CheckoutBuilder::make()
        ->paymentMethod('BANK_TRANSFER')
        ->currency('VND')
        ->orderInvoiceNumber($invoiceNumber)
        ->orderAmount($amount)
        ->operation('PURCHASE')
        ->orderDescription($description)
        ->successUrl(route('payment.confirmWithCode', ['paymentCode' => $invoiceNumber]))
        ->build();

    // Hiển thị form checkout ra giao diện
    return $sepay->checkout()->generateFormHtml($checkoutData);
}
