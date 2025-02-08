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
    $ext = explode('.', $url);
    $ext = count(explode('.', $url)) > 0 ? array_pop($ext) : $ext;

    if(in_array($ext, ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'webm'])) {
        $imgUrl = str_replace('/upload/',"/upload/w_{$width},h_{$height},f_webp/", $url);
        $imgUrl = str_replace(".{$ext}", '.webp', $imgUrl);
    } else {
        $imgUrl = str_replace('/upload/',"/upload/f_webp/c_thumb,w_{$width},h_{$height}/", $url);
    }

    return $imgUrl;
}

function getZaloLink($number) : ?string {
    if(! $number) return '';

    return "https://zalo.me/{$number}";
}

function getShortPrice(string $input) : ?string
{
    $length = strlen(trim($input));

    if($length>= 10) return str_replace('.', ',', $input / 10**9) . ' tỷ';
    elseif($length>= 7) return str_replace('.', ',', $input / 10**6) . ' triệu';
    elseif($length>= 4) return str_replace('.', ',', $input / 10**3) . ' ngàn';

    return null;
}