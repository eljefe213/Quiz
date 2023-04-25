<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Question = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $Created_At = null;

    #[ORM\ManyToOne(inversedBy: 'Question')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'Question')]
    private Collection $game_id;

    #[ORM\OneToMany(mappedBy: 'question_id', targetEntity: Answer::class)]
    private Collection $Answer;

    public function __construct()
    {
        $this->game_id = new ArrayCollection();
        $this->Answer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->Question;
    }

    public function setQuestion(string $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->Created_At;
    }

    public function setCreatedAt(\DateTimeImmutable $Created_At): self
    {
        $this->Created_At = $Created_At;

        return $this;
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

    /**
     * @return Collection<int, Game>
     */
    public function getGameId(): Collection
    {
        return $this->game_id;
    }

    public function addGameId(Game $gameId): self
    {
        if (!$this->game_id->contains($gameId)) {
            $this->game_id->add($gameId);
            $gameId->addQuestion($this);
        }

        return $this;
    }

    public function removeGameId(Game $gameId): self
    {
        if ($this->game_id->removeElement($gameId)) {
            $gameId->removeQuestion($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswer(): Collection
    {
        return $this->Answer;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->Answer->contains($answer)) {
            $this->Answer->add($answer);
            $answer->setQuestionId($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->Answer->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestionId() === $this) {
                $answer->setQuestionId(null);
            }
        }

        return $this;
    }
}
