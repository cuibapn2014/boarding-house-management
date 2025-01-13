<?php
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
function numberRemoveComma(mixed $input) {
    return str_replace(',', '.', str_replace('.', '', $input));
}

function numberFormatVi(int $input) {
    return number_format($input, 0, ',', '.');
}

function dateForHumman(string $date) {
    return \Carbon\Carbon::parse($date)->diffForHumans();
}

function resizeImageCloudinary(string $url, float $width, float $height) : string {
    $imgUrl = str_replace('/upload/',"/upload/f_webp/c_thumb,w_{$width},h_{$height}/", $url);

    return $imgUrl;
}

function getZaloLink($number) : ?string {
    if(! $number) return '';

    return "https://zalo.me/{$number}";
}