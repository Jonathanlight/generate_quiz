<?php

namespace App\Entity;

use App\Entity\Traits\DateInfoTrait;
use App\Entity\Traits\UserInfoTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet e-mail")
 * @method string getUserIdentifier()
 */
class User implements UserInterface
{
    use UserInfoTrait;
    use DateInfoTrait;

    const STATUS_WOMAN = 'Mrs';
    const STATUS_MAN = 'Mr';

    const PROFIL_VALIDATED = 1;
    const PROFIL_NON_VALIDATED = 0;
    const PROFIL_NULL = 2;

    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Quiz::class, mappedBy="user")
     */
    private $quizes;

    /**
     * @ORM\OneToMany(targetEntity=AnswerUser::class, mappedBy="user")
     */
    private $answerUsers;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="user")
     */
    private $sessions;

    /**
     * @ORM\OneToMany(targetEntity=Session::class, mappedBy="teacher")
     */
    private $sessionTeacher;

    public function __construct()
    {
        $this->quizes = new ArrayCollection();
        $this->answerUsers = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->sessionTeacher = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) ucfirst($this->getEmail());
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizes(): Collection
    {
        return $this->quizes;
    }

    public function addQuize(Quiz $quize): self
    {
        if (!$this->quizes->contains($quize)) {
            $this->quizes[] = $quize;
            $quize->setUser($this);
        }

        return $this;
    }

    public function removeQuize(Quiz $quize): self
    {
        if ($this->quizes->removeElement($quize)) {
            // set the owning side to null (unless already changed)
            if ($quize->getUser() === $this) {
                $quize->setUser(null);
            }
        }

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
            $answerUser->setUser($this);
        }

        return $this;
    }

    public function removeAnswerUser(AnswerUser $answerUser): self
    {
        if ($this->answerUsers->removeElement($answerUser)) {
            // set the owning side to null (unless already changed)
            if ($answerUser->getUser() === $this) {
                $answerUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions[] = $session;
            $session->setUser($this);
        }

        return $this;
    }

    public function removeSession(Session $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getUser() === $this) {
                $session->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessionTeacher(): Collection
    {
        return $this->sessionTeacher;
    }

    public function addSessionTeacher(Session $sessionTeacher): self
    {
        if (!$this->sessionTeacher->contains($sessionTeacher)) {
            $this->sessionTeacher[] = $sessionTeacher;
            $sessionTeacher->setTeacher($this);
        }

        return $this;
    }

    public function removeSessionTeacher(Session $sessionTeacher): self
    {
        if ($this->sessionTeacher->removeElement($sessionTeacher)) {
            // set the owning side to null (unless already changed)
            if ($sessionTeacher->getTeacher() === $this) {
                $sessionTeacher->setTeacher(null);
            }
        }

        return $this;
    }
}
