<?php

use App\Helpers\DateHelper;
use Carbon\Carbon;

/**
 * Format tanggal ke format Indonesia
 * 
 * @param mixed $date
 * @param string $format
 * @return string
 */
if (!function_exists('formatIndonesian')) {
    function formatIndonesian($date, $format = 'd F Y H:i')
    {
        return DateHelper::formatIndonesian($date, $format);
    }
}

/**
 * Format waktu relatif (Jam lalu, hari lalu, dll)
 * 
 * @param mixed $date
 * @return string
 */
if (!function_exists('timeAgoIndonesian')) {
    function timeAgoIndonesian($date)
    {
        return DateHelper::timeAgoIndonesian($date);
    }
}

/**
 * Format tanggal lengkap dengan waktu dalam format Indonesia
 * 
 * @param mixed $date
 * @return string
 */
if (!function_exists('formatLengkap')) {
    function formatLengkap($date)
    {
        return DateHelper::formatLengkap($date);
    }
}

/**
 * Format tanggal saja
 * 
 * @param mixed $date
 * @return string
 */
if (!function_exists('formatDate')) {
    function formatDate($date)
    {
        return DateHelper::formatDate($date);
    }
}

/**
 * Format waktu saja
 * 
 * @param mixed $date
 * @return string
 */
if (!function_exists('formatTime')) {
    function formatTime($date)
    {
        return DateHelper::formatTime($date);
    }
}
