<?php

namespace App\Controller\User;

use App\Entity\Answer;
use App\Entity\OptionChoice;
use App\Form\AnswerType;
use App\Manager\AnswerManager;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/answer")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/", name="app_answer_index", methods={"GET"})
     */
    public function index(AnswerRepository $answerRepository): Response
    {
        return $this->render('answer/index.html.twig', [
            'answers' => $answerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_answer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AnswerRepository $answerRepository): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answerRepository->add($answer);
            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_answer_show", methods={"GET"})
     */
    public function show(Answer $answer): Response
    {
        return $this->render('answer/show.html.twig', [
            'answer' => $answer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_answer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Answer $answer, AnswerRepository $answerRepository): Response
    {
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answerRepository->add($answer);
            return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('answer/edit.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_answer_delete", methods={"POST"})
     */
    public function delete(Request $request, Answer $answer, AnswerRepository $answerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $answerRepository->remove($answer);
        }

        return $this->redirectToRoute('app_answer_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/done_answer/{id}", name="app_answer_answer", methods={"GET", "POST"})
     */
    public function answer(Request $request, OptionChoice $optionChoice, AnswerManager $answerManager): Response
    {
        $answerManager->checkOptionChoose($optionChoice);

        return $this->redirectToRoute('app_question_show', ['id' => $optionChoice->getQuestion()->getId()]);
    }

    /**
     * @Route("/cancel_answer/{id}", name="app_answer_cancel_answer", methods={"GET", "POST"})
     */
    public function cancel_answer(Request $request, OptionChoice $optionChoice, AnswerManager $answerManager): Response
    {
        $answerManager->checkOptionChoose($optionChoice);

        return $this->redirectToRoute('app_question_show', ['id' => $optionChoice->getQuestion()->getId()]);
    }
}
