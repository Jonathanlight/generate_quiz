<?php

namespace App\Manager;

use App\Entity\Quiz;
use App\Entity\Theme;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ThemeManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $themeRepository;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->themeRepository = $this->em->getRepository(Theme::class);
    }

    public function getThemes()
    {
        return $this->themeRepository->findAll();
    }

    public function new(Theme $theme)
    {
        $this->em->flush();
    }

    public function delete(Theme $theme)
    {
        $this->em->remove($theme);
        $this->em->flush();
    }

    public function update(Theme $theme)
    {
        $this->em->flush();
    }
}