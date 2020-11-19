<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\Hand;
use App\Entity\Round;
use SplFileObject;

class HandsFileParser implements FileParser
{
    /**
     * @param string $filename
     * @return Game
     */
    public function parse(string $filename): Game
    {
        $rounds = [];
        foreach(new SplFileObject($filename) as $line)
        {
            if (trim($line == '')) continue;
            $player1CardsString = substr($line, 0, 14);
            $Player1Cards = HandEvaluator::fromString($player1CardsString);
            $cardsPlayer2String = substr($line, 15, 14);
            $Player2Cards = HandEvaluator::fromString($cardsPlayer2String);
            $rounds[] = new Round(new Hand($Player1Cards), new Hand($Player2Cards));
        }
        return new Game($rounds);
    }
}
