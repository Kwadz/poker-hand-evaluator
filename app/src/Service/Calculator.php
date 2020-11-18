<?php


namespace App\Service;


use Doctrine\Common\Collections\Collection;

interface Calculator
{
    /**
     * @param Collection $rounds
     * @return int
     */
    public function calculatePlayer1Wins(Collection $rounds): int;
}
