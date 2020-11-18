<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $gameFilename;

    /**
     * The rounds that contains the hands of the players.
     *
     * @ORM\OneToMany(targetEntity=Round::class, mappedBy="game")
     * @var Collection|Round[]
     */
    private Collection $rounds;

    public function __construct(array $rounds)
    {
        $this->rounds = new ArrayCollection($rounds);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGameFilename()
    {
        return $this->gameFilename;
    }

    public function setGameFilename($gameFilename)
    {
        $this->gameFilename = $gameFilename;

        return $this;
    }

    /**
     * @return Round[]|Collection<int, Round>
     */
    public function getRounds(): Collection
    {
        return $this->rounds;
    }

    /**
     * @param Round[]|Collection<int, Round> $rounds
     */
    public function setRounds(Collection $rounds): void
    {
        $this->rounds = $rounds;
    }
}
