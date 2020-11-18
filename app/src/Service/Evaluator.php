<?php


namespace App\Service;

interface Evaluator
{
    /**
     * @param array $cards
     * @return int
     */
    public function evaluate(array $cards): int;
}
