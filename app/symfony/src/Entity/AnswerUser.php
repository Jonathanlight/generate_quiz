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
     * @ORM\Column(type="boolean")
     */
    private $response;

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

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="answerUsers")
     */
    private $session;

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

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getResponse(): ?bool
    {
        return $this->response;
    }

    public function setResponse(bool $response): self
    {
        $this->response = $response;

        return $this;
    }
}
