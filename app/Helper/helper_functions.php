<?php
function numberRemoveComma(mixed $input) {
    return str_replace(',', '.', str_replace('.', '', $input));
}

function numberFormatVi(int $input) {
    return number_format($input, 0, ',', '.');
}