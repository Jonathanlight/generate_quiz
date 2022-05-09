<?php

namespace App\Manager;

use App\Entity\Answer;
use App\Entity\AnswerUser;
use App\Entity\OptionChoice;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Session;
use App\Entity\User;
use App\Services\MessageService;
use App\Services\TranslatorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class SessionManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ObjectRepository
     */
    protected $sessionRepository;

    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    protected $optionChoiceRepository;

    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    protected $answerRepository;

    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    protected $answerUserRepository;

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
        $this->sessionRepository = $this->em->getRepository(Session::class);
        $this->optionChoiceRepository = $this->em->getRepository(OptionChoice::class);
        $this->answerRepository = $this->em->getRepository(Answer::class);
        $this->answerUserRepository = $this->em->getRepository(AnswerUser::class);
    }

    public function getSessions()
    {
        return $this->sessionRepository->findAll();
    }

    public function getSessionsByTeacher(User $teacher)
    {
        return $this->sessionRepository->findByTeacher($teacher);
    }

    public function getSessionsByStudent(User $student)
    {
        return $this->sessionRepository->findByUser($student);
    }

    public function isSessionsIsAttribute(User $student, Quiz $quiz)
    {
        return $this->sessionRepository->checkSessionIsAttribute($student, $quiz);
    }

    public function evaluationQuiz(array $data, Session $session, User $student): void
    {
        $questions = $session->getQuiz()->getQuestions();

        foreach($questions as $question) {
            $idOptionChoiceValue = $data['option-choice-value-'.$question->getId()];
            $optionChoice = $this->optionChoiceRepository->find($idOptionChoiceValue);
            $answer = $this->answerRepository->findOneByOptionChoice($optionChoice);

            $answerUser = new AnswerUser();
            $answerUser->setOptionChoice($optionChoice);
            $answerUser->setQuestion($question);
            $answerUser->setUser($student);
            $answerUser->setSession($session);

            if ($answer instanceof Answer) {
                $answerUser->setResponse(true);
            }else{
                $answerUser->setResponse(false);
            }

            $this->em->persist($answerUser);
        }

        $this->em->flush();
    }

    public function setMarkBySession(Session $session): void
    {
        $answerUserResponses = $this->answerUserRepository->findBySession($session);

        $reponse = 0;
        $compter = 0;

        foreach ($answerUserResponses as $answerUserResponse) {
            if ($answerUserResponse->getResponse()) {
                ++$reponse;
            }

            ++$compter;
        }

        $session->setPoint($compter);
        $session->setMark($reponse);
        $this->em->persist($session);
        $this->em->flush();
    }

    public function new(Session $session): void
    {
        $reference = uniqid('', '');
        $session->setReference($reference);
        $session->setPoint(0);
        $session->setMark(0);
        $session->setCreatedAt(new \DateTime());
        $this->em->persist($session);
        $this->em->flush();
    }

    public function delete(Session $session)
    {
        $this->em->remove($session);
        $this->em->flush();
    }

    public function update(Session $session)
    {
        $this->em->flush();
    }
}