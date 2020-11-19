<?php

namespace App\Service;

use App\Entity\Game;

interface FileParser
{
    /**
     * @param string $filename
     * @return Game
     */
    public function parse(string $filename): Game;
}
