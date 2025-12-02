<?php

use Carbon\Carbon;

function addWorkingHours(Carbon $start, int $hours): Carbon
{
    $dt = $start->copy();
    $remaining = $hours;

    while ($remaining > 0) {

        // Пропустити вихідні
        if ($dt->isWeekend()) {
            $dt = $dt->next(Carbon::MONDAY)->setTime(0, 1, 0);
            continue;
        }

        // Додати годину
        $dt->addHour();
        $remaining--;

        // Якщо зайшли у вихідні — перенести на понеділок
        if ($dt->isWeekend()) {
            $dt = $dt->next(Carbon::MONDAY)->setTime(0, 1, 0);
        }
    }

    return $dt;
}


function workingSecondsBetween(Carbon $start, Carbon $end): int
{
    $total = 0;
    $current = $start->copy();

    while ($current < $end) {

        // Вихідні — переход на понеділок 00:01
        if ($current->isWeekend()) {
            $current = $current->next(Carbon::MONDAY)->setTime(0, 1);
            continue;
        }

        // Якщо сьогодні робочий, але час ДО 00:01 → перейти до 00:01
        if ($current->hour === 0 && $current->minute === 0) {
            $current->setTime(0, 1);
        }

        // Кінець робочого дня
        $endOfDay = $current->copy()->setTime(23, 59, 59);

        // Якщо кінець періоду менший — використовуємо його
        $rangeEnd = $end->lt($endOfDay) ? $end : $endOfDay;

        // Додаємо секунди (тільки якщо rangeEnd > current!)
        if ($rangeEnd > $current) {
            $total += $current->diffInSeconds($rangeEnd);
        }

        // Перейти на наступний день 00:01
        $current = $current->copy()->addDay()->setTime(0, 1, 0);
    }

    return $total;
}



