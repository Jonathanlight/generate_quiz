<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 */
class Mark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $point;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $mark;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="marks")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="marks")
     */
    private $quiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoint(): ?float
    {
        return $this->point;
    }

    public function setPoint(?float $point): self
    {
        $this->point = $point;

        return $this;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(?float $mark): self
    {
        $this->mark = $mark;

        return $this;
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

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }
}
