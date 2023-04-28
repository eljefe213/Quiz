<?php

namespace App\Service;

use App\Repository\ScoreRepository;

class ScoreManager
{
    private ScoreRepository $scoreRepository;

    public function __construct(ScoreRepository $scoreRepository)
    {
        $this->scoreRepository = $scoreRepository;
    }

    public function findAllScoresDESC()
    {
        return $this->scoreRepository->findAllScoresDESC();
    }
}
