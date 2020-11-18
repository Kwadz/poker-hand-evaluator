<?php


namespace App\Service;

use App\Entity\Card;

interface Evaluator
{
    /**
     * @param Card[] $cards
     * @return int
     */
    public function evaluate(array $cards): int;
}
