<?php

namespace App\Manager;

use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class QuestionManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $questionRepository;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->questionRepository = $this->em->getRepository(Question::class);
    }

    public function getQuestions()
    {
        return $this->questionRepository->findAll();
    }

    public function getQuestionsByQuiz(Quiz $quiz)
    {
        return $this->questionRepository->findByQuiz($quiz);
    }

    public function new(Question $question)
    {
        $this->em->persist($question);
        $this->em->flush();
    }

    public function delete(Question $question)
    {
        $this->em->remove($question);
        $this->em->flush();
    }

    public function update(Question $question)
    {
        $this->em->flush();
    }
}