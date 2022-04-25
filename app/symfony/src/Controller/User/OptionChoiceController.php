<?php

namespace App\Controller\User;

use App\Entity\OptionChoice;
use App\Form\OptionChoiceType;
use App\Repository\OptionChoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/option/choice")
 */
class OptionChoiceController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="app_option_choice_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OptionChoice $optionChoice, OptionChoiceRepository $optionChoiceRepository): Response
    {
        $form = $this->createForm(OptionChoiceType::class, $optionChoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionChoiceRepository->add($optionChoice);
            return $this->redirectToRoute('app_option_choice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('option_choice/edit.html.twig', [
            'option_choice' => $optionChoice,
            'form' => $form,
        ]);
    }
}
