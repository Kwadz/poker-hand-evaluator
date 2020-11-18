<?php


namespace App\Service;


use Doctrine\Common\Collections\Collection;

interface Calculator
{
    public function calculatePlayer1Wins(Collection $rounds): int;
}
