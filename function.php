<?php
function kiraJumlah($senarai) {
    $jumlah = 0;
    foreach ($senarai as $item) {
        list($nama, $harga) = explode('|', $item);
        $jumlah += (float)$harga;
    }
    return $jumlah;
}
?>
