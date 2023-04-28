<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Score = null;

    #[ORM\OneToOne(mappedBy: 'score', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "score_id", referencedColumnName: "id")]
    private ?Game $Game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?string
    {
        return $this->Score;
    }

    public function setScore(string $Score): self
    {
        $this->Score = $Score;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(Game $game): self
    {
        // set the owning side of the relation if necessary
        if ($game->getScore() !== $this) {
            $game->setScore($this);
        }

        $this->Game = $game;

        return $this;
    }
}
