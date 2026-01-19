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
        ->build();

    // Hiển thị form checkout ra giao diện
    return $sepay->checkout()->generateFormHtml($checkoutData);
}
