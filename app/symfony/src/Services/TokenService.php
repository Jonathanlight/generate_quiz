<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;

class TokenService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public function generate(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * @param string $reference
     * @return bool
     * @throws \Exception
     */
    public function isValid(string $reference): bool
    {
        $user = $this->userRepository->findOneBy(['reference' => $reference]);

        if ($user instanceof User) {
            return new \DateTime() < $user->getPasswordResetDate();
        }

        return false;
    }
}
