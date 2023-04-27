<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game')]
    public function index(QuestionRepository $questionRepository): Response
    {
        $questions = $questionRepository->findAll();
        $questions_length = count($questions);

        // Mélange des questions
        shuffle($questions);

        // Sélection des 3 premières questions
        $questions = array_slice($questions, 0, 3);

        return $this->render('game/index.html.twig', [
            'questions' => $questions,
        ]);
    }
}
