<?php

namespace App\Entity;

use App\Service\Tables\Tables;
use InvalidArgumentException;

class Card
{
    private int $value;  // Format: xxxAKQJT 98765432 CDHSrrrr xxPPPPPP

    // Ranks
    public const DEUCE = 0;
    public const TREY  = 1;
    public const FOUR  = 2;
    public const FIVE  = 3;
    public const SIX   = 4;
    public const SEVEN = 5;
    public const EIGHT = 6;
    public const NINE  = 7;
    public const TEN   = 8;
    public const JACK  = 9;
    public const QUEEN = 10;
    public const KING  = 11;
    public const ACE   = 12;

    // Suits
    public const CLUBS    = 0x8000;
    public const DIAMONDS = 0x4000;
    public const HEARTS   = 0x2000;
    public const SPADES   = 0x1000;

    // Rank symbols
    public const RANKS = "23456789TJQKA";
    public const SUITS = "shdc";

    /**
     * Card constructor.
     * Creates a new card with the given rank and suit.
     *
     * @param int $rank the rank of the card, e.g. {@link Card::SIX}
     * @param int $suit the suit of the card, e.g. {@link Card::CLUBS}
     */
    public function __construct(int $rank, int $suit)
    {
        if (!$this->isValidRank($rank)) {
            throw new InvalidArgumentException("Invalid rank.");
        }
        if (!$this->isValidSuit($suit)) {
            throw new InvalidArgumentException("Invalid suit.");
        }

        $this->value = (1 << ($rank + 16)) | $suit | ($rank << 8) | Tables::PRIMES[$rank];
    }


    /**
     * Create a new {@link Card} instance from the given string.
     * The string should be a two-character insensitive string where the first character
     * is the rank and the second character is the suit. For example, "Kc" means
     * the king of clubs, and "As" means the ace of spades.
     *
     * @param string $string Card to create as a string.
     * @return Card a new instance corresponding to the given string.
     */
    public static function fromString(string $string): Card {
        if (strlen($string) != 2) {
            throw new InvalidArgumentException("Card string must be with length of exactly 2.");
        }
        $rank = stripos(self::RANKS, $string[0]);
        $index = stripos(self::SUITS, $string[1]);
        $suit = ($index !== false) ? self::SPADES << $index : 0x0;

        return new Card($rank, $suit);
    }
    
    /**
     * Returns the rank of the card.
     *
     * @return int rank of the card as an integer.
     * @see Card::ACE
     * @see Card::DEUCE
     * @see Card::TREY
     * @see Card::FOUR
     * @see Card::FIVE
     * @see Card::SIX
     * @see Card::SEVEN
     * @see Card::EIGHT
     * @see Card::NINE
     * @see Card::TEN
     * @see Card::JACK
     * @see Card::QUEEN
     * @see Card::KING
     */
    public function getRank(): int {
        return ($this->value >> 8) & 0xF;
    }

    /**
     * Returns the suit of the card.
     *
     * @return int Suit of the card as an integer.
     * @see Card::SPADES
     * @see Card::HEARTS
     * @see Card::DIAMONDS
     * @see Card::CLUBS
     */
    public function getSuit(): int {
        return $this->value & 0xF000;
    }

    /**
     * Returns whether the given rank is valid or not.
     *
     * @param int $rank rank to check.
     * @return bool true if the rank is valid, false otherwise.
     */
    private function isValidRank(int $rank): bool {
        return $rank >= self::DEUCE && $rank <= self::ACE;
    }

    /**
     * Returns whether the given suit is valid or not.
     *
     * @param int $suit suit to check.
     * @return bool true if the suit is valid, false otherwise.
     */
    private function isValidSuit(int $suit): bool {
        return $suit == self::CLUBS || $suit == self::DIAMONDS || $suit == self::HEARTS || $suit == self::SPADES;
    }

    /**
     * Returns the value of the card as an integer.
     * The value is represented as the bits <code>xxxAKQJT 98765432 CDHSrrrr xxPPPPPP</code>,
     * where <code>x</code> means unused, <code>AKQJT 98765432</code> are bits turned on/off
     * depending on the rank of the card, <code>CDHS</code> are the bits corresponding to the
     * suit, and <code>PPPPPP</code> is the prime number of the card.
     *
     * @return int the value of the card.
     */
    public function getValue(): int {
        return $this->value;
    }

    /**
     * Returns a string representation of the card.
     * For example, the king of spades is "Ks", and the jack of hearts is "Jh".
     *
     * @return string a string representation of the card.
     */
    public function toString() {
        $rank = self::RANKS[$this->getRank()];
        $suit = self::SUITS[(int) (log($this->getSuit()) / log(2)) - 12];
        return "" . $rank . $suit;
    }
}
