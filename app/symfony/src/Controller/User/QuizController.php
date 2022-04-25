<?php

namespace App\Controller\User;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Form\QuizType;
use App\Manager\QuestionManager;
use App\Manager\QuizManager;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/", name="app_quiz_index", methods={"GET"})
     */
    public function index(QuizManager $quizManager): Response
    {
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizManager->getQuizs(),
        ]);
    }

    /**
     * @Route("/new", name="app_quiz_new", methods={"GET", "POST"})
     */
    public function new(Request $request, QuizManager $quizManager): Response
    {
        $user = $this->getUser();
        $quiz = new Quiz();
        $quiz->setUser($user);
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizManager->new($quiz);
            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_quiz_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Quiz $quiz, QuestionManager $questionManager): Response
    {
        $question = new Question();
        $question->setQuiz($quiz);
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionManager->new($question);
            return $this->redirectToRoute('app_quiz_show', ['id' => $quiz->getId()]);
        }

        return $this->render('quiz/show.html.twig', [
            'questions' => $questionManager->getQuestionsByQuiz($quiz),
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_quiz_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Quiz $quiz, QuizManager $quizManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quizManager->update($quiz);
            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_quiz_delete", methods={"POST"})
     */
    public function delete(Request $request, Quiz $quiz, QuizManager $quizManager): Response
    {
        $quizManager->delete($quiz);

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}
