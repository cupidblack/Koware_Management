<?php
declare(strict_types=1);

/**
 * Millennium Timezone calculation core.
 * Preserves the existing sabbatical-date.php date formula and UTC convention.
 */

if (!function_exists('millennium_timezone_ordinal')) {
    function millennium_timezone_ordinal(int $n): string {
        if ($n > 3 && $n < 21) return $n . 'th';
        return match ($n % 10) {
            1 => $n . 'st',
            2 => $n . 'nd',
            3 => $n . 'rd',
            default => $n . 'th',
        };
    }
}

if (!function_exists('millennium_timezone_jubilee')) {
    function millennium_timezone_jubilee(int $year): string {
        $hebrew = $year + 3760;
        $raw = ($hebrew - 2503) / 50;
        $whole = (int) floor($raw);
        $fraction = $raw - $whole;
        $yy = (int) floor(($fraction * 100) / 2);
        return $whole . 'Y' . str_pad((string) $yy, 2, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('millennium_timezone_calculate')) {
    function millennium_timezone_calculate(?DateTimeImmutable $at = null): array {
        $utc = new DateTimeZone('UTC');
        $now = ($at ?? new DateTimeImmutable('now', $utc))->setTimezone($utc);
        $historicalHourMap = [0 => 0, 1 => 14, 2 => 3, 3 => 17, 4 => 6, 5 => 20, 6 => 10];
        $daysShort = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $daysFull = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $epoch = new DateTimeImmutable('1899-12-30 00:00:00', $utc);
        $diffDays = ($now->getTimestamp() - $epoch->getTimestamp()) / 86400;
        $j = (((($diffDays / 365.262541748) + 1900) / 7) - 2 - 184) * 365.262541748;
        $historical = $epoch->modify('+' . (int) round($j) . ' days');

        $weekday = (int) $now->format('w');
        $base = $historicalHourMap[$weekday] ?? 0;
        $secondsToday = ((int) $now->format('H') * 3600)
            + ((int) $now->format('i') * 60)
            + (int) $now->format('s');
        $historicalSeconds = ($secondsToday / 86400) * 14400;
        $hour = (int) floor($historicalSeconds / 3600);
        $minute = (int) floor(fmod($historicalSeconds, 3600) / 60);
        $second = (int) floor(fmod($historicalSeconds, 60));
        $historical = $historical->setTime(($base + $hour) % 24, $minute, $second);

        return [
            'day' => $daysShort[(int) $historical->format('w')] . $daysFull[(int) $now->format('w')],
            'date' => millennium_timezone_ordinal((int) $historical->format('j')),
            'month' => $months[(int) $historical->format('n') - 1],
            'jubilee' => millennium_timezone_jubilee((int) $now->format('Y')),
            'reference_year' => (int) $historical->format('Y'),
            'local' => $historical->format('H:i:s'),
            'current' => $now->format(DateTimeInterface::ATOM),
            'probe' => null,
            'historical_time' => $historical->format('H:i:s'),
            'reference_iso' => $historical->format(DateTimeInterface::ATOM),
            'now_iso' => $now->format(DateTimeInterface::ATOM),
        ];
    }
}
