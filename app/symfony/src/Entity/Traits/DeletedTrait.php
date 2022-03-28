<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeletedTrait
{
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deleted;

    /**
     * @return \DateTime|null
     */
    public function getDeleted(): ?\DateTime
    {
        return $this->deleted;
    }

    /**
     * @param \DateTime $deleted
     */
    public function setDeleted(\DateTime $deleted): void
    {
        $this->deleted = $deleted;
    }
}