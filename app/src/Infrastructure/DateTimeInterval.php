<?php

namespace App\Infrastructure;

use DateInterval;

class DateTimeInterval
{
    public static function createHumanInterval(DateInterval $dateInterval): string
    {

        // @todo добавить множественное значение.
        $timeInterval = [];
        if ($dateInterval->y) {
            $timeInterval[] = $dateInterval->y . ' год';
        }
        if ($dateInterval->m) {
            $timeInterval[] = $dateInterval->m . ' месяц';
        }
        if ($dateInterval->d) {
            $timeInterval[] = $dateInterval->d . ' день';
        }
        if ($dateInterval->h) {
            $timeInterval[] = $dateInterval->h . ' час';
        }
        if ($dateInterval->i) {
            $timeInterval[] = $dateInterval->i . ' минута';
        }
        if ($dateInterval->s) {
            $timeInterval[] = $dateInterval->s . ' секунда';
        }

        return implode(' ', $timeInterval);
    }
}
