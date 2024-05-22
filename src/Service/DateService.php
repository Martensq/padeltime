<?php

namespace App\Service;

use DateTime;
use DateInterval;
use Exception;

class DateService
{
    /**
     * Obtenir les 14 prochains jours à partir d'aujourd'hui.
     *
     * @return array
     * @throws Exception
     */
    public function getNextFourteenDays(): array
    {
        $days = [];
        $today = new DateTime();

        for ($i = 0; $i < 14; $i++) {
            $days[] = clone $today;
            $today->add(new DateInterval('P1D'));
        }

        return $days;
    }
}