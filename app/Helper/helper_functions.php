<?php

use Illuminate\Support\Str;

function numberRemoveComma(mixed $input) {
    return str_replace(',', '.', str_replace('.', '', $input));
}

function numberFormatVi(int $input) {
    return number_format($input, 0, ',', '.');
}

function convertDateWithFormat(?string $input, string $formatInput, string $formatOutput = 'Y-m-d') {
    if(!$input || trim($input) === '') return null;

    $date = \Carbon\Carbon::createFromFormat($formatInput, $input);

    return $date->format($formatOutput);
}

function getLinkPreview($id, $title) {
    $title = Str::slug($title);
    return "https://nhatrototsaigon.com/danh-sach-cho-thue/{$id}/{$title}";
}