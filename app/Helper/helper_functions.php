<?php
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