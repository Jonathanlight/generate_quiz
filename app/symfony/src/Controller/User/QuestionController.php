<?php

namespace App\Controller\User;

use App\Entity\OptionChoice;
use App\Entity\Question;
use App\Form\OptionChoiceType;
use App\Form\QuestionType;
use App\Manager\OptionChoiceManager;
use App\Manager\QuestionManager;
use App\Repository\OptionChoiceRepository;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_question_new", methods={"GET", "POST"})
     */
    public function new(Request $request, QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionRepository->add($question);
            //return $this->redirectToRoute('app_question_index');
        }

        return $this->renderForm('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_question_show", methods={"GET", "POST"})
     */
    public function show(Question $question, Request $request, OptionChoiceManager $optionChoiceManager): Response
    {
        $optionChoice = new OptionChoice();
        $optionChoice->setQuestion($question);
        $formChoice = $this->createForm(OptionChoiceType::class, $optionChoice);
        $formChoice->handleRequest($request);


        if ($formChoice->isSubmitted() && $formChoice->isValid()) {
            $optionChoiceManager->new($optionChoice);
            return $this->redirectToRoute('app_question_show', ['id' => $question->getId()]);
        }

        $optionChoices = $optionChoiceManager->choiceByQuestion($question);

        return $this->render('question/show.html.twig', [
            'option_choice' => $optionChoice,
            'choices' => $optionChoices,
            'formChoice' => $formChoice->createView(),
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/option_choice_delete", name="app_option_choice_delete", methods={"GET","POST"})
     */
    public function app_option_choice_delete(OptionChoice $optionChoice, OptionChoiceManager $optionChoiceManager): Response
    {
        $optionChoiceManager->delete($optionChoice);

        return $this->redirectToRoute('app_question_show', ['id' => $optionChoice->getQuestion()->getId()]);
    }

    /**
     * @Route("/{id}/edit", name="app_question_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Question $question, QuestionRepository $questionRepository): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionRepository->add($question);
            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_question_delete", methods={"POST"})
     */
    public function delete(Request $request, Question $question, QuestionManager $questionManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $questionManager->delete($question);
        }

        return $this->redirectToRoute('app_quiz_show', ['id' => $question->getQuiz()->getId()]);
    }
}
