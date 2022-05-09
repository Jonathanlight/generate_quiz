<?php

namespace App\Manager;

use App\Entity\Answer;
use App\Entity\OptionChoice;
use App\Entity\Question;
use App\Services\MessageService;
use App\Services\TranslatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class AnswerManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $answerRepository;

    /**
     * @var MessageService
     */
    protected MessageService $messageService;

    /**
     * @var TranslatorService
     */
    protected TranslatorService $translatorService;

    public function __construct(
        EntityManagerInterface $em,
        MessageService $messageService,
        TranslatorService $translatorService
    )
    {
        $this->em = $em;
        $this->messageService = $messageService;
        $this->translatorService = $translatorService;
        $this->answerRepository = $this->em->getRepository(Answer::class);
    }

    public function getAnswers()
    {
        return $this->answerRepository->findAll();
    }

    public function getAnswerByOptionChoice(OptionChoice $optionChoice, Question $question)
    {
        return $this->answerRepository->getOptionChoose($optionChoice, $question);
    }

    public function checkOptionChoose(OptionChoice $optionChoice)
    {
        $answer = $this->answerRepository->getOneByOptionChoose($optionChoice);

        if ($answer instanceof Answer) {
            $this->delete($answer);

            $message = $this->translatorService->translate('app.message.flash.answer_removed');

            return $this->messageService->addSuccess($message);
        } else {
            $question = $optionChoice->getQuestion();
            $answerIsset = $this->answerRepository->getOneByQuestion($question);

            if ($answerIsset instanceof Answer) {
                $message = $this->translatorService->translate('app.message.flash.answer_not_add');

                return $this->messageService->addWarning($message);
            } else {
                $answer = new Answer();
                $answer->setQuestion($question);
                $answer->setOptionChoice($optionChoice);
                $this->em->persist($answer);
                $this->em->flush();

                $message = $this->translatorService->translate('app.message.flash.answer_add');

                return $this->messageService->addSuccess($message);
            }
        }
    }

    public function new(Answer $answer)
    {
        $this->em->persist($answer);
        $this->em->flush();
    }

    public function delete(Answer $answer)
    {
        $this->em->remove($answer);
        $this->em->flush();
    }

    public function update(Answer $answer)
    {
        $this->em->flush();
    }
}