<?php

namespace App\Manager;

use App\Entity\OptionChoice;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class OptionChoiceManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $repository;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository(OptionChoice::class);
    }

    public function choiceByQuestion(Question $question)
    {
        return $this->repository->findByQuestion($question);
    }

    public function new(OptionChoice $optionChoice)
    {
        $this->em->persist($optionChoice);
        $this->em->flush();
    }

    public function delete(OptionChoice $optionChoice)
    {
        $this->em->remove($optionChoice);
        $this->em->flush();
    }

    public function update(OptionChoice $optionChoice)
    {
        $this->em->flush();
    }
}