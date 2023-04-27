<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerFormType;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    #[Route('/answer', name: 'app_answer')]
    public function index(): Response
    {
        return $this->render('answer/index.html.twig', [
            'controller_name' => 'AnswerController',
        ]);
    }

    #[Route('/question/create/{id}/answer/create', name: 'create_answer')]
    public function create_answer(Request $request, EntityManagerInterface $entityManager, $id, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($id);
        $answer = new Answer();

        $form = $this->createForm(AnswerFormType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($question) {
                $answer->setQuestionId($question);
            }
            $answer->setIsTrue(true);

            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('app_question');
        }
        return $this->render('answer/add.html.twig', [
            'question' => $question,
            'answerForm' => $form->createView(),
        ]);
    }
    #[Route('/question/create/{id}/answer/create/false', name: 'create_false_answer')]
    public function create_false_answer(Request $request, EntityManagerInterface $entityManager, $id, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($id);
        $answer = new Answer();

        $form = $this->createForm(AnswerFormType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($question) {
                $answer->setQuestionId($question);
            }
            $answer->setIsTrue(false);

            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('app_question');
        }
        return $this->render('answer/add.html.twig', [
            'question' => $question,
            'answerForm' => $form->createView(),
        ]);
    }

    #[Route('/answer/edit/{id}', name: 'edit_answer',  methods: ['GET', 'POST'])]
    public function edit_answer(Request $request, AnswerRepository $answerRepository, EntityManagerInterface $em, $id)
    {
        $post = $answerRepository->find($id);

        //dd($post);
        $form = $this->createForm(AnswerFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_question');
        }

        return $this->render('answer/edit.html.twig', [
            'answerForm' => $form->createView(),
        ]);
    }

    #[Route('/answer/delete/{id}', name: 'delete_answer', methods: ['GET'])]
    public function delete_answer(AnswerRepository $answerRepository, Answer $answer): Response
    {
        $answerRepository->remove($answer, true);

        return $this->redirectToRoute('app_question');
    }
}
