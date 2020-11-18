<?php

use App\Entity\Card;
use App\Service\HandEvaluator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\IOException;

class HandEvaluatorTest extends TestCase
{
    private HandEvaluator $handEvaluator;

    public function setUp(): void {
        $this->handEvaluator = new HandEvaluator();
    }
    
    public function testEvaluateTooFewCards(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->handEvaluator->evaluate(
            [
                new Card(Card::KING, Card::HEARTS),
                new Card(Card::QUEEN, Card::CLUBS),
                new Card(Card::JACK, Card::DIAMONDS),
                new Card(Card::TEN, Card::SPADES),
            ]
        );
    }
    public function testEvaluateTooManyCards(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->handEvaluator->evaluate([
            new Card(Card::KING, Card::HEARTS),
            new Card(Card::QUEEN, Card::CLUBS),
            new Card(Card::JACK, Card::DIAMONDS),
            new Card(Card::TEN, Card::SPADES),
            new Card(Card::ACE, Card::HEARTS),
            new Card(Card::EIGHT, Card::CLUBS),
        ]);
    }

    public function testEvaluateIllegalHand(): void {
        $this->expectException(InvalidArgumentException::class);
        $this->handEvaluator->evaluate([
            new Card(Card::KING, Card::HEARTS),
            new Card(Card::KING, Card::HEARTS),
            new Card(Card::JACK, Card::DIAMONDS),
            new Card(Card::TEN, Card::SPADES),
            new Card(Card::ACE, Card::HEARTS),
        ]);
    }

    public function testEvaluateRoyalFlush(): void {
        $suits = [
            Card::CLUBS,
            Card::DIAMONDS,
            Card::HEARTS,
            Card::SPADES,
        ];
        foreach ($suits as $suit) {
            $this->assertEquals(1, $this->handEvaluator->evaluate([
                new Card(Card::KING, $suit),
                new Card(Card::QUEEN, $suit),
                new Card(Card::JACK, $suit),
                new Card(Card::TEN, $suit),
                new Card(Card::ACE, $suit),
            ]));
        }
    }

    public function testEvaluateSevenHigh(): void {
        $this->assertEquals(7462, $this->handEvaluator->evaluate([
            new Card(Card::SEVEN, Card::HEARTS),
            new Card(Card::FIVE, Card::CLUBS),
            new Card(Card::FOUR, Card::DIAMONDS),
            new Card(Card::TREY, Card::SPADES),
            new Card(Card::DEUCE, Card::HEARTS),
        ]));
    }

    public function testEvaluatePair(): void {
        $this->assertEquals(6185, $this->handEvaluator->evaluate([
            new Card(Card::DEUCE, Card::HEARTS),
            new Card(Card::DEUCE, Card::DIAMONDS),
            new Card(Card::TREY, Card::CLUBS),
            new Card(Card::FOUR, Card::CLUBS),
            new Card(Card::FIVE, Card::CLUBS),
        ]));
    }

    /**
     * @throws IOException
     */
    public function testEvaluateAllHands() : void {
        $expectedHandCount = 2598960;
        $count = 0;
        $filename = __DIR__ . "/HandTestValues.txt";
        $this->assertFileExists($filename);
        foreach(new SplFileObject($filename) as $line) {
            if (trim($line) == '') { // skip blank lines
                continue;
            }
            $cardsString = substr($line, 0, strrpos($line, " "));
            $valueString = substr($line, strrpos($line, " ") + 1);
            $cards = HandEvaluator::fromString($cardsString);
            $expected = intval($valueString);
            $actual = $this->handEvaluator->evaluate($cards);
            $this->assertEquals(
                $expected,
                $actual,
                "Evaluation of hand '" . HandEvaluator::toString($cards) . "' (parsed from '" . $cardsString . "') failed."
            );
            ++$count;
        }
        $this->assertEquals($count, $expectedHandCount);
    }

    public function testTooFewFromString(): void {
            $this->expectException(InvalidArgumentException::class);
            HandEvaluator::fromString("Kd 5s Jc Ah");
    }

    public function testTooManyFromString(): void {
            $this->expectException(InvalidArgumentException::class);
            HandEvaluator::fromString("Kd 5s Jc Ah Qc Th");
    }

    public function testInvalidFromString(): void {
            $this->expectException(InvalidArgumentException::class);
            HandEvaluator::fromString("Kd 5s Jc Ah Qx");
    }

    public function testValidFromString(): void {
        $cards = HandEvaluator::fromString("Kd 5s Jc Ah Qc");

        $kingOfDiamonds = $cards[0];
        $fiveOfSpades = $cards[1];
        $jackOfClubs = $cards[2];
        $aceOfHearts = $cards[3];
        $queenOfClubs = $cards[4];

        $this->assertEquals(Card::KING, $kingOfDiamonds->getRank());
        $this->assertEquals(Card::DIAMONDS, $kingOfDiamonds->getSuit());

        $this->assertEquals(Card::FIVE, $fiveOfSpades->getRank());
        $this->assertEquals(Card::SPADES, $fiveOfSpades->getSuit());

        $this->assertEquals(Card::JACK, $jackOfClubs->getRank());
        $this->assertEquals(Card::CLUBS, $jackOfClubs->getSuit());

        $this->assertEquals(Card::ACE, $aceOfHearts->getRank());
        $this->assertEquals(Card::HEARTS, $aceOfHearts->getSuit());

        $this->assertEquals(Card::QUEEN, $queenOfClubs->getRank());
        $this->assertEquals(Card::CLUBS, $queenOfClubs->getSuit());
    }

    public function testToString(): void {
        $this->assertEquals("Kd 5s Jc Ah Qc", HandEvaluator::toString([
            new Card(Card::KING, Card::DIAMONDS),
            new Card(Card::FIVE, Card::SPADES),
            new Card(Card::JACK, Card::CLUBS),
            new Card(Card::ACE, Card::HEARTS),
            new Card(Card::QUEEN, Card::CLUBS),
            ])
        );
    }
}
