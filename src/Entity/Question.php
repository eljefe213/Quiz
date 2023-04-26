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



    #[ORM\OneToMany(mappedBy: 'question_id', targetEntity: Answer::class)]
    private Collection $Answer;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'Question')]
    private Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
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

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->addQuestion($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeQuestion($this);
        }

        return $this;
    }
}
