<?php

namespace App\Service;

use App\Service\Evaluator;
use Doctrine\Common\Collections\Collection;

class PlayerWinsCalculator implements Calculator
{
    /**
     * The evaluator that gives the value of the given hand.
     *
     * @var Evaluator
     */
    private Evaluator $handEvaluator;

    public function __construct(Evaluator $handEvaluator)
    {
        $this->handEvaluator = $handEvaluator;
    }

    /**
     * Calculates how many hands player 1 wins.
     *
     * @param Collection $rounds
     * @return int
     */
    public function calculatePlayer1Wins(Collection $rounds): int
    {
        $player1WinsCount = 0;
        foreach ($rounds as $round) {
            $player1Value = $this->handEvaluator->evaluate($round->getPlayer1Hand()->getCards());
            $player2Value = $this->handEvaluator->evaluate($round->getPlayer2Hand()->getCards());
            if ($player1Value > $player2Value) ++$player1WinsCount;
        }
        return $player1WinsCount;
    }
}
