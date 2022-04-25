<?php

namespace App\Manager;

use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class QuizManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $quizRepository;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->quizRepository = $this->em->getRepository(Quiz::class);
    }

    public function getQuizs()
    {
        return $this->quizRepository->findAll();
    }

    public function new(Quiz $quiz)
    {
        $this->em->flush();
    }

    public function delete(Quiz $quiz)
    {
        $this->em->remove($quiz);
        $this->em->flush();
    }

    public function update(Quiz $quiz)
    {
        $this->em->flush();
    }
}