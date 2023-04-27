<?php


namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Game;
use App\Entity\Score;
use App\Repository\AnswerRepository;
use App\Repository\GameRepository;
use App\Repository\QuestionRepository;
use App\Repository\ScoreRepository;
use App\Service\ScoreManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game', name: 'app_game_create')]
    public function create_game(QuestionRepository $questionRepository, Request $request, EntityManagerInterface $entityManager,): Response
    {
        $questions = $questionRepository->findAll();
        $questions_length = count($questions);

        // Mélange des questions
        shuffle($questions);

        // Sélection des 3 premières questions
        $questions = array_slice($questions, 0, 3);

        // récupérer l'utilisateur connecté
        $user = $this->getUser();
        // créer une nouvelle instance de l'objet Game
        $game = new Game();

        $game->setUserId($user);

        $score = new Score();
        $score->setGameId($game);
        $score->setScore(0);

        // ajouter chaque question sélectionnée à l'objet Game
        foreach ($questions as $question) {
            $game->addQuestion($question);
        }

        // enregistrer l'objet Game en base de données
        $entityManager->persist($game);
        $entityManager->flush();

        // récupérer l'ID de l'objet Game
        $gameId = $game->getId();


        // rediriger l'utilisateur vers la page de jeu
        return $this->redirectToRoute('app_game', ['id' => $gameId], Response::HTTP_SEE_OTHER);
    }

    #[Route('/game/{id}', name: 'app_game')]
    public function index(QuestionRepository $questionRepository, $id, GameRepository $gameRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = $gameRepository->find($id);
        //dd($game);
        return $this->render('game/index.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/game/{id_game}/{id_answer}', name: 'game_question_response')]
    public function question_response($id_game, $id_answer, GameRepository $gameRepository, AnswerRepository $answerRepository, ScoreRepository $scoreRepository)
    {
        $answer = $answerRepository->find($id_answer);

        if ($answer->isIsTrue() === true) {
            $game = $gameRepository->find($id_game);
            $score_id = $game->getScore();
            $score = $scoreRepository->find($score_id);

            $score_get = $score->getScore();
            $score->setScore($score_get + 1);

            $scoreRepository->save($score, true);
        }
        //dd($question);
        return $this->redirectToRoute('app_game', ['id' => $id_game], Response::HTTP_SEE_OTHER);
    }
}
