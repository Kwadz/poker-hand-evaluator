<?php

namespace App\Service\Tables;

use Exception;

/**
 * Lookup tables for making calculations faster.
 */
class Tables
{
    private const CARDS_IN_DECK = 52;
    private const HAND_COMBINATIONS = 2598960;
    public const PRIMES = [2, 3, 5, 7, 11, 13, 17, 19, 23, 29, 31, 37, 41];
    private const DECK = [
    98306, 164099, 295429, 557831, 1082379, 2131213, 4228625, 8423187, 16812055, 33589533, 67144223, 134253349,
        268471337, 81922, 147715, 279045, 541447, 1065995, 2114829, 4212241, 8406803, 16795671, 33573149, 67127839,
        134236965, 268454953, 73730, 139523, 270853, 533255, 1057803, 2106637, 4204049, 8398611, 16787479, 33564957,
        67119647, 134228773, 268446761, 69634, 135427, 266757, 529159, 1053707, 2102541, 4199953, 8394515, 16783383,
        33560861, 67115551, 134224677, 268442665,
    ];

    /**
     * Tables constructor.
     */
    public function __construct()
    {
        define('HANDS', self::generateHandsConstant());
    }


    private static function generateHandsConstant()
    {
        $hands = [];

        // Generate every possible 5-card hand
        $totalHands = 0;
        $hand       = [];
        for ($a = 0; $a < self::CARDS_IN_DECK - 4; $a++) {
            $hand[0] = self::DECK[$a];
            for ($b = $a + 1; $b < self::CARDS_IN_DECK - 3; $b++) {
                $hand[1] = self::DECK[$b];
                for ($c = $b + 1; $c < self::CARDS_IN_DECK - 2; $c++) {
                    $hand[2] = self::DECK[$c];
                    for ($d = $c + 1; $d < self::CARDS_IN_DECK - 1; $d++) {
                        $hand[3] = self::DECK[$d];
                        for ($e = $d + 1; $e < self::CARDS_IN_DECK; $e++) {
                            $hand[4]              = self::DECK[$e];
                            $hands[$totalHands++] = $hand;
                        }
                    }
                }
            }
        }
        if ($totalHands != count($hands)) {
            throw new Exception("Invalid rank.");
        }

        return $hands;
    }
}
