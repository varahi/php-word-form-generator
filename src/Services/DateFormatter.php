<?php

namespace App\Services;

class DateFormatter
{
    public static function russianMonth(int $monthNumber): string
    {
        $months = [
            1 => 'Января',
            2 => 'Февраля',
            3 => 'Марта',
            4 => 'Апреля',
            5 => 'Мая',
            6 => 'Июня',
            7 => 'Июля',
            8 => 'Августа',
            9 => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
        ];

        if ($monthNumber < 1 || $monthNumber > 12) {
            throw new \InvalidArgumentException('Номер месяца должен быть от 1 до 12');
        }

        return $months[$monthNumber];
    }

    public static function formatRussianDate(string $dateString): array
    {
        [$day, $month, $year] = explode('.', $dateString);

        return [
            'day' => (int)$day,
            'month' => self::russianMonth((int)$month),
            'year' => $year,
            'month_number' => (int)$month
        ];
    }
}
