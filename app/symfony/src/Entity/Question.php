<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="questions")
     */
    private $quiz;

    /**
     * @ORM\OneToMany(targetEntity=AnswerUser::class, mappedBy="question")
     */
    private $answerUsers;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question")
     */
    private $answers;

    /**
     * @ORM\OneToMany(targetEntity=OptionChoice::class, mappedBy="question")
     */
    private $optionChoices;

    public function __construct()
    {
        $this->answerUsers = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->optionChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection<int, AnswerUser>
     */
    public function getAnswerUsers(): Collection
    {
        return $this->answerUsers;
    }

    public function addAnswerUser(AnswerUser $answerUser): self
    {
        if (!$this->answerUsers->contains($answerUser)) {
            $this->answerUsers[] = $answerUser;
            $answerUser->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswerUser(AnswerUser $answerUser): self
    {
        if ($this->answerUsers->removeElement($answerUser)) {
            // set the owning side to null (unless already changed)
            if ($answerUser->getQuestion() === $this) {
                $answerUser->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OptionChoice>
     */
    public function getOptionChoices(): Collection
    {
        return $this->optionChoices;
    }

    public function addOptionChoice(OptionChoice $optionChoice): self
    {
        if (!$this->optionChoices->contains($optionChoice)) {
            $this->optionChoices[] = $optionChoice;
            $optionChoice->setQuestion($this);
        }

        return $this;
    }

    public function removeOptionChoice(OptionChoice $optionChoice): self
    {
        if ($this->optionChoices->removeElement($optionChoice)) {
            // set the owning side to null (unless already changed)
            if ($optionChoice->getQuestion() === $this) {
                $optionChoice->setQuestion(null);
            }
        }

        return $this;
    }
}
