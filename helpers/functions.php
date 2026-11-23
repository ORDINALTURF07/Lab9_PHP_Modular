=<?php
// helpers/functions.php

function e(string $nilai): string
{
    return htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8');
}

function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function hitungDiskon(float $harga, float $persen = 10): float
{
    return $harga * ($persen / 100);
}

function hitungTotal(float $harga, int $jumlah): float
{
    return $harga * $jumlah;
}

function konversiCelciusKeFahrenheit(float $c): float
{
    return ($c * 9 / 5) + 32;
}

function tentukanStatusNilai(float $nilai): string
{
    if ($nilai >= 85) {
        return 'A';
    }
    if ($nilai >= 75) {
        return 'B';
    }
    if ($nilai >= 60) {
        return 'C';
    }
    return 'D';
}

function validasiEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function ringkasTeks(string $teks, int $maks = 60): string
{
    if (mb_strlen($teks, 'UTF-8') <= $maks) {
        return $teks;
    }
    return mb_substr($teks, 0, $maks, 'UTF-8') . '...';
}

function is_select($var, $val): string
{
    return $var == $val ? 'selected="selected"' : '';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_login']);
}