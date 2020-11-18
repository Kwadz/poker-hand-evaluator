<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * Hand of the player one.
     *
     * @ORM\OneToOne(targetEntity="Hand")
     * @ORM\JoinColumn(name="player1_hand_id", referencedColumnName="id")
     */
    private Hand $player1Hand;

    /**
     * Hand of the player two.
     *
     * @ORM\OneToOne(targetEntity="Hand")
     * @ORM\JoinColumn(name="player2_hand_id", referencedColumnName="id")
     */
    private Hand $player2Hand;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     */
    private Game $game;

    /**
     * Round constructor.
     *
     * @param Hand $player1Hand
     * @param Hand $player2Hand
     */
    public function __construct(Hand $player1Hand, Hand $player2Hand)
    {
        $this->player1Hand = $player1Hand;
        $this->player2Hand = $player2Hand;
    }

    /**
     * @return Hand
     */
    public function getPlayer1Hand(): Hand
    {
        return $this->player1Hand;
    }

    /**
     * @return Hand
     */
    public function getPlayer2Hand(): Hand
    {
        return $this->player2Hand;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

}
