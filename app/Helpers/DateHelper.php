<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTimeZone;

class DateHelper
{
    protected static function toCarbon($date): ?Carbon
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof Carbon) {
            return $date->copy()->setTimezone(
                new DateTimeZone(config('app.timezone'))
            );
        }

        return Carbon::parse(
            $date,
            new DateTimeZone(config('app.timezone'))
        );
    }

    /**
     * Format tanggal ke format Indonesia
     */
    public static function formatIndonesian($date, $format = 'd F Y H:i')
    {
        $carbon = self::toCarbon($date);

        if (!$carbon) {
            return '-';
        }

        return $carbon
            ->locale('id')
            ->isoFormat($format);
    }

    /**
     * Format waktu relatif (x menit yang lalu)
     */
    public static function timeAgoIndonesian($date)
    {
        $carbon = self::toCarbon($date);

        if (!$carbon) {
            return '-';
        }

        return $carbon
            ->locale('id')
            ->diffForHumans();
    }

    /**
     * Format tanggal lengkap
     */
    public static function formatLengkap($date)
    {
        return self::formatIndonesian($date, 'dddd, DD MMMM YYYY HH:mm');
    }

    /**
     * Format tanggal saja
     */
    public static function formatDate($date)
    {
        return self::formatIndonesian($date, 'DD MMMM YYYY');
    }

    /**
     * Format waktu saja
     */
    public static function formatTime($date)
    {
        return self::formatIndonesian($date, 'HH:mm');
    }
}
