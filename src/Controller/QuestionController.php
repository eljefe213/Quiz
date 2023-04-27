<?php


namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;

class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'app_question')]
    public function index(QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAll();
        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/question/create', name: 'create_question')]
    public function create_question(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setCreatedAt(new DateTimeImmutable());
            if ($this->getUser()) {
                $question->setUserId($this->getUser());
            }

            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('app_question');
        }
        return $this->render('question/add.html.twig', [
            'questionForm' => $form->createView(),
        ]);
    }
    #[Route('/question/edit/{id}', name: 'edit_question',  methods: ['GET', 'POST'])]
    public function edit_question(Request $request, QuestionRepository $questionRepository, EntityManagerInterface $em, $id)
    {
        $post = $questionRepository->find($id);

        //dd($post);
        $form = $this->createForm(QuestionFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_question');
        }

        return $this->render('question/edit.html.twig', [
            'questionForm' => $form->createView(),
        ]);
    }

    #[Route('/question/delete/{id}', name: 'delete_question', methods: ['GET'])]
    public function delete_question(QuestionRepository $questionRepository, Question $question): Response
    {
        $questionRepository->remove($question, true);

        return $this->redirectToRoute('app_question');
    }
}
