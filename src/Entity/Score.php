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

    #[ORM\OneToOne(mappedBy: 'Score', cascade: ['persist', 'remove'])]
    private ?Game $game_id = null;

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

    public function getGameId(): ?Game
    {
        return $this->game_id;
    }

    public function setGameId(Game $game_id): self
    {
        // set the owning side of the relation if necessary
        if ($game_id->getScore() !== $this) {
            $game_id->setScore($this);
        }

        $this->game_id = $game_id;

        return $this;
    }
}
