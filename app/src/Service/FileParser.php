<?php


namespace App\Service;


use App\Entity\Game;

interface FileParser
{
    public function parse(string $filename): Game;
}
