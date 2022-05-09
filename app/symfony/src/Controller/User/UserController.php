<?php

namespace App\Controller\User;

use App\Entity\Quiz;
use App\Entity\Session;
use App\Manager\SessionManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/quiz")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/session-quiz", name="student_quiz")
     */
    public function session_qcm(Request $request, SessionManager $sessionManager)
    {
        $user = $this->getUser();

        return $this->render('quiz/session_quiz.html.twig', [
            'sessions' => $sessionManager->getSessionsByStudent($user),
        ]);
    }

    /**
     * @Route("/session/{id}", name="student_quiz_start", requirements={"id":"\d+"})
     */
    public function qcm_start(Session $session, Request $request, SessionManager $sessionManager)
    {
        $user = $this->getUser();

        $isSessionQuizsIsAttribute = $sessionManager->isSessionsIsAttribute($user, $session->getQuiz());

        if(!$isSessionQuizsIsAttribute) {
            return $this->redirectToRoute('student_quiz');
        }

        if($session->getMark() > 0) {
            return $this->redirectToRoute('student_quiz');
        }

        if ($request->getMethod() === "POST") {
            $data = $request->request->all();
            $sessionManager->evaluationQuiz($data, $session, $user);
            $sessionManager->setMarkBySession($session);
            return $this->redirectToRoute('student_quiz');
        }

        return $this->render('quiz/quiz.html.twig', [
            'quiz' => $session->getQuiz(),
        ]);
    }
}