<?php

declare(strict_types=1);

/**
 * Lightweight Jalali (Shamsi) <-> Gregorian conversion helpers.
 * بدون وابستگی خارجی
 */

function _jalali_div(int $a, int $b): int
{
    return (int)floor($a / $b);
}

/** @return array{0:int,1:int,2:int} [gy, gm, gd] */
function jalali_to_gregorian(int $jy, int $jm, int $jd): array
{
    $jy += 1595;
    $days = -355668
        + (365 * $jy)
        + _jalali_div($jy, 33) * 8
        + _jalali_div(($jy % 33) + 3, 4)
        + $jd
        + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);

    $gy = 400 * _jalali_div($days, 146097);
    $days %= 146097;

    if ($days > 36524) {
        $gy += 100 * _jalali_div(--$days, 36524);
        $days %= 36524;
        if ($days >= 365) {
            $days++;
        }
    }

    $gy += 4 * _jalali_div($days, 1461);
    $days %= 1461;

    if ($days > 365) {
        $gy += _jalali_div($days - 1, 365);
        $days = ($days - 1) % 365;
    }

    $gd = $days + 1;

    $sal_a = [0, 31, ((($gy % 4 === 0) && ($gy % 100 !== 0)) || ($gy % 400 === 0)) ? 29 : 28,
        31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

    $gm = 0;
    for ($i = 1; $i <= 12; $i++) {
        if ($gd <= $sal_a[$i]) {
            $gm = $i;
            break;
        }
        $gd -= $sal_a[$i];
    }

    return [$gy, $gm, $gd];
}

/** @return array{0:int,1:int,2:int} [jy, jm, jd] */
function gregorian_to_jalali(int $gy, int $gm, int $gd): array
{
    $g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
    $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
    $days = 355666
        + (365 * $gy)
        + _jalali_div($gy2 + 3, 4)
        - _jalali_div($gy2 + 99, 100)
        + _jalali_div($gy2 + 399, 400)
        + $gd
        + $g_d_m[$gm - 1];

    $jy = -1595 + (33 * _jalali_div($days, 12053));
    $days %= 12053;

    $jy += 4 * _jalali_div($days, 1461);
    $days %= 1461;

    if ($days > 365) {
        $jy += _jalali_div($days - 1, 365);
        $days = ($days - 1) % 365;
    }

    if ($days < 186) {
        $jm = 1 + _jalali_div($days, 31);
        $jd = 1 + ($days % 31);
    } else {
        $jm = 7 + _jalali_div($days - 186, 30);
        $jd = 1 + (($days - 186) % 30);
    }

    return [$jy, $jm, $jd];
}

function gdate_to_jalali_str(string $gdate, string $sep = '/'): string
{
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $gdate, $m)) {
        return $gdate;
    }
    [$jy, $jm, $jd] = gregorian_to_jalali((int)$m[1], (int)$m[2], (int)$m[3]);
    return sprintf('%04d%s%02d%s%02d', $jy, $sep, $jm, $sep, $jd);
}

/**
 * Accepts 'YYYY/MM/DD' or 'YYYY-MM-DD' (Jalali) and returns 'YYYY-MM-DD' (Gregorian).
 */
function jdate_str_to_gdate(string $jdate): ?string
{
    $jdate = trim($jdate);
    if (!preg_match('/^(\d{4})[\/-](\d{1,2})[\/-](\d{1,2})$/', $jdate, $m)) {
        return null;
    }
    [$gy, $gm, $gd] = jalali_to_gregorian((int)$m[1], (int)$m[2], (int)$m[3]);
    return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
}