<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answers")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=OptionChoice::class, inversedBy="answers")
     */
    private $optionChoice;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOptionChoice(): ?OptionChoice
    {
        return $this->optionChoice;
    }

    public function setOptionChoice(?OptionChoice $optionChoice): self
    {
        $this->optionChoice = $optionChoice;

        return $this;
    }
}
