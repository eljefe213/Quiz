<?php

namespace App\Controller;

use App\Service\ScoreManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    #[Route('/scores', name: 'app_score')]
    public function index(ScoreManager $scoreManager): Response
    {
        $scores = $scoreManager->findAllScoresDESC();

        return $this->render('score/index.html.twig', [
            'scores' => $scores,
        ]);
    }
}
