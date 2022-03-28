<?php

namespace App\Entity;

use App\Repository\AnswerUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerUserRepository::class)
 */
class AnswerUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="answerUsers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=OptionChoice::class, inversedBy="answerUsers")
     */
    private $optionChoice;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answerUsers")
     */
    private $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getOptionChoice(): ?OptionChoice
    {
        return $this->optionChoice;
    }

    public function setOptionChoice(?OptionChoice $optionChoice): self
    {
        $this->optionChoice = $optionChoice;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}