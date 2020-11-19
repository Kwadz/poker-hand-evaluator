<?php

namespace App\Service;

use Doctrine\Common\Collections\Collection;

interface Counter
{
    /**
     * @param Collection $rounds
     * @return int
     */
    public function countPlayer1Wins(Collection $rounds): int;
}
