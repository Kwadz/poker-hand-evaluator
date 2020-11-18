<?php

namespace App\Service;

use App\Entity\Card;
use App\Service\Tables\Flushes;
use App\Service\Tables\Hash\Adjust;
use App\Service\Tables\Unique;
use App\Service\Tables\Values;
use InvalidArgumentException;

class HandEvaluator implements Evaluator
{

    /**
     * Evaluates the given hand and returns its value as an integer.
     * Based on Kevin Suffecool's 5-card hand evaluator and with Paul Senzee's pre-computed hash.
     *
     * @param Card[] $cards a hand of cards to evaluate
     * @return int the value of the hand as an integer between 1 and 7462
     */
    public function evaluate(array $cards): int
    {
        // Only 5-card hands are supported
        if ($cards == null || count($cards) != 5) {
            throw new InvalidArgumentException("Exactly 5 cards are required.");
        }

        // Binary representations of each card
        $c1 = $cards[0]->getValue();
        $c2 = $cards[1]->getValue();
        $c3 = $cards[2]->getValue();
        $c4 = $cards[3]->getValue();
        $c5 = $cards[4]->getValue();

        // No duplicate cards allowed
        if ($this->hasDuplicates([$c1, $c2, $c3, $c4, $c5])) {
            throw new InvalidArgumentException("Illegal hand.");
        }

        // Calculate index in the flushes/unique table
        $index = ($c1 | $c2 | $c3 | $c4 | $c5) >> 16;

        // Flushes, including straight flushes
        if (($c1 & $c2 & $c3 & $c4 & $c5 & 0xF000) != 0) {
            return Flushes::TABLE[$index];
        }

        // Straight and high card hands
        $value = Unique::TABLE[$index];
        if ($value != 0) {
            return $value;
        }

        // Remaining cards
        $product = ($c1 & 0xFF) * ($c2 & 0xFF) * ($c3 & 0xFF) * ($c4 & 0xFF) * ($c5 & 0xFF);
        $hash = $this->hash($product);
        return Values::TABLE[$this->hash($product)];
    }

    /**
     * Creates an array of 5 cards from string.
     *
     * @param string $string
     * @return array
     */
    public static function fromString(string $string): array
    {
        $parts = explode(" ", $string);
        $cards = [];

        if (count($parts) != 5) {
            throw new InvalidArgumentException("Exactly 5 cards are required.");
        }
        foreach ($parts as $key => $part) {
            $cards[] = Card::fromString($part);
        }

        return $cards;
    }

    /**
     * Converts the given hand into concatenation of their string representations.
     *
     * @param Card[] $cards a hand of cards
     * @return string a concatenation of the string representations of the given cards
     */
    public static function toString(array $cards): string {
        $string = '';
        for ($i = 0; $i < count($cards); $i++) {
            $string .= $cards[$i]->toString();
            if ($i < count($cards) - 1)
                $string .= ' ';
        }

    return $string;
}

    /**
     * Checks if the given array of values has any duplicates.
     *
     * @param int[] values the values to check
     * @return bool true if the values contain duplicates, false otherwise
     */
    private function hasDuplicates(array $values)
    {
        sort($values);
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i] == $values[$i - 1]) {
                return true;
            }
        }

        return false;
    }

    /**
     * The hashing technique based on Bob Jenkin’s perfect hashing algorithm.
     *
     * @param int $key
     * @return int
     */
    private function hash(int $key)
    {
        $key += 0xE91AAA35;
        $key ^= $this->uRShift($key, 16);
        $key += $key << 8;
        $key = $this->toInt32($key);
        $key ^= $this->uRShift($key, 4);
        $key = $this->toInt32($key);
        $b = ($this->uRShift($key, 8)) & 0x1FF;
        $a = $this->uRShift(($this->toInt32($key + ($key << 2))), 19);

        return ($a ^ Adjust::TABLE[$b]);
    }

    /**
     * Unsigned right shift operator.
     * Shifts a zero into the leftmost position.
     *
     * @param $a
     * @param $b
     * @return int
     */
    private function uRShift($a, $b)
    {
        if($b == 0) return $a;
        return ($a >> $b) & ~(1 << (8 * PHP_INT_SIZE - 1) >> ( $b - 1 ));
    }

    /**
     * Converts integer to 32 bits
     *
     * @param int $int
     * @return int
     */
    private function toInt32(int $int) {
        return $int & 0xFFFFFFFF;
    }
}
