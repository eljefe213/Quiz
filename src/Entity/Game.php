<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Game')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;



    #[ORM\OneToOne(inversedBy: 'game_id', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Score $Score = null;

    #[ORM\OneToMany(mappedBy: 'Game', targetEntity: GameQuestion::class)]
    private Collection $gameQuestions;

    public function __construct()
    {
        $this->gameQuestions = new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getScore(): ?Score
    {
        return $this->Score;
    }

    public function setScore(Score $Score): self
    {
        $this->Score = $Score;

        return $this;
    }

    /**
     * @return Collection<int, GameQuestion>
     */

    public function getGameQuestions(): Collection
    {
        return $this->gameQuestions;
    }

    public function addGameQuestion(GameQuestion $gameQuestion): self
    {
        if (!$this->gameQuestions->contains($gameQuestion)) {
            $this->gameQuestions[] = $gameQuestion;
            $gameQuestion->setGame($this);
        }

        return $this;
    }

    public function removeGameQuestion(GameQuestion $gameQuestion): self
    {
        if ($this->gameQuestions->removeElement($gameQuestion)) {
            if ($gameQuestion->getGame() === $this) {
                $gameQuestion->setGame(null);
            }
        }

        return $this;
    }
}
