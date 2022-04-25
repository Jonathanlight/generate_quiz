<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $point;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $mark;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="sessions")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sessions")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToMany(targetEntity=AnswerUser::class, mappedBy="session")
     */
    private $answerUsers;

    public function __construct()
    {
        $this->answerUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoint(): ?float
    {
        return $this->point;
    }

    public function setPoint(float $point): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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
            $answerUser->setSession($this);
        }

        return $this;
    }

    public function removeAnswerUser(AnswerUser $answerUser): self
    {
        if ($this->answerUsers->removeElement($answerUser)) {
            // set the owning side to null (unless already changed)
            if ($answerUser->getSession() === $this) {
                $answerUser->setSession(null);
            }
        }

        return $this;
    }
}
