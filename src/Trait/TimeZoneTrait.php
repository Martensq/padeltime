<?php

namespace App\Trait;

    trait TimeZoneTrait
    {
        public function changeTimeZone(string $timeZone): void
        {
            setlocale(LC_TIME, 'fr_FR');
            \date_default_timezone_set($timeZone);
        }
    }