<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use App\Entity\Traits\AdminInfoTrait;
use App\Entity\Traits\DateInfoTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 */
class Admin implements UserInterface
{
    use AdminInfoTrait;
    use DateInfoTrait;

    /**
     * @param $username
     */
    public function loadUserByUsername($username)
    {
        // TODO: Implement loadUserByUsername() method.
    }

    /**
     * @param UserInterface $user
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    /**
     * @param $class
     */
    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }
}
