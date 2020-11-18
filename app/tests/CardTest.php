<?php

namespace App\Tests;

use App\Entity\Card;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testSmoke()
    {
        $card = new Card(Card::KING, Card::DIAMONDS);
        $this->assertInstanceOf( Card::class, $card);
    }

    public function testIllegalRank()
    {
        $this->expectException(InvalidArgumentException::class);
        new Card(-1, Card::DIAMONDS);
    }

    public function testIllegalSuit()
    {
        $this->expectException(InvalidArgumentException::class);
        new Card(Card::KING, -1);
    }

    public function testGetRank()
    {
        $this->assertEquals(Card::KING, (new Card(Card::KING, Card::DIAMONDS))->getRank());
        $this->assertEquals(Card::FIVE, (new Card(Card::FIVE, Card::SPADES))->getRank());
        $this->assertEquals(Card::JACK, (new Card(Card::JACK, Card::CLUBS))->getRank());
        $this->assertEquals(Card::SIX, (new Card(Card::SIX, Card::HEARTS))->getRank());
        $this->assertEquals(Card::NINE, (new Card(Card::NINE, Card::DIAMONDS))->getRank());
    }

    public function testGetSuit()
    {
        $this->assertEquals(Card::DIAMONDS, (new Card(Card::KING, Card::DIAMONDS))->getSuit());
        $this->assertEquals(Card::SPADES, (new Card(Card::FIVE, Card::SPADES))->getSuit());
        $this->assertEquals(Card::CLUBS, (new Card(Card::JACK, Card::CLUBS))->getSuit());
        $this->assertEquals(Card::HEARTS, (new Card(Card::SIX, Card::HEARTS))->getSuit());
    }

    public function testGetValue()
    {
        $this->assertEquals(0b00001000000000000100101100100101, (new Card(Card::KING, Card::DIAMONDS))->getValue());
        $this->assertEquals(0b00000000000010000001001100000111, (new Card(Card::FIVE, Card::SPADES))->getValue());
        $this->assertEquals(0b00000010000000001000100100011101, (new Card(Card::JACK, Card::CLUBS))->getValue());
    }

    public function testFromString()
    {
        $kingOfDiamonds = Card::fromString("Kd");
        $this->assertEquals(Card::KING, $kingOfDiamonds->getRank());
        $this->assertEquals(Card::DIAMONDS, $kingOfDiamonds->getSuit());

        $fiveOfSpades = Card::fromString("5s");
        $this->assertEquals(Card::FIVE, $fiveOfSpades->getRank());
        $this->assertEquals(Card::SPADES, $fiveOfSpades->getSuit());

        $jackOfClubs = Card::fromString("Jc");
        $this->assertEquals(Card::JACK, $jackOfClubs->getRank());
        $this->assertEquals(Card::CLUBS, $jackOfClubs->getSuit());
    }

    public function testFromStringInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        Card::fromString("Kx");
        Card::fromString("Xd");
        Card::fromString("Xx");
    }

    public function testFromStringEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        Card::fromString("");
    }

    public function testFromStringTooShort()
    {
        $this->expectException(InvalidArgumentException::class);
        Card::fromString("K");
    }

    public function testFromStringTooLong()
    {
        $this->expectException(InvalidArgumentException::class);
        Card::fromString("Kd Qs");
    }

    public function testToString()
    {
        $this->assertEquals("Kd", (new Card(Card::KING, Card::DIAMONDS))->toString());
        $this->assertEquals("5s", (new Card(Card::FIVE, Card::SPADES))->toString());
        $this->assertEquals("Jc", (new Card(Card::JACK, Card::CLUBS))->toString());
    }
}
