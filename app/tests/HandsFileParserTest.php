<?php

use App\Entity\Card;
use App\Service\HandsFileParser;
use PHPUnit\Framework\TestCase;

class HandsFileParserTest  extends TestCase
{
    public function testParse(): void {
        $handFileParser = new HandsFileParser();
        $filename = __DIR__ . "/UploadedFile.txt";
        $this->assertFileExists($filename);
        $rounds = $handFileParser->parse($filename);
        foreach ($rounds as $round) {
            foreach ($round->getPlayer1Hand() as $card) {
                $this->assertInstanceOf(Card::class, $card);
            }
            foreach ($round->getPlayer2Hand() as $card) {
                $this->assertInstanceOf(Card::class, $card);
            }
        }
    }
}
