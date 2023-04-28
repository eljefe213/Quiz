<?php

namespace App\Entity;

use App\Repository\GameQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameQuestionRepository::class)]
class GameQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $IsResponse = null;

    #[ORM\ManyToOne(inversedBy: 'Question_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $Game = null;

    #[ORM\ManyToOne(inversedBy: 'Game')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $Question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsResponse(): ?bool
    {
        return $this->IsResponse;
    }

    public function setIsResponse(bool $IsResponse): self
    {
        $this->IsResponse = $IsResponse;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->Game;
    }

    public function setGame(?Game $game): self
    {
        $this->Game = $game;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->Question = $question;

        return $this;
    }
}
