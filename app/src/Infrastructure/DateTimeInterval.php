<?php

namespace App\Infrastructure;

use DateInterval;

class DateTimeInterval
{
    public static function createHumanInterval(DateInterval $dateInterval): string
    {
        $timeInterval = [];
        if ($dateInterval->y) {
            $timeInterval[] = $dateInterval->y . ' year';
        }
        if ($dateInterval->m) {
            $timeInterval[] = $dateInterval->m . ' month';
        }
        if ($dateInterval->d) {
            $timeInterval[] = $dateInterval->d . ' day';
        }
        if ($dateInterval->h) {
            $timeInterval[] = $dateInterval->h . ' hour';
        }
        if ($dateInterval->i) {
            $timeInterval[] = $dateInterval->i . ' minute';
        }
        if ($dateInterval->s) {
            $timeInterval[] = $dateInterval->s . ' second';
        }

        return implode(' ', $timeInterval);
    }
}
