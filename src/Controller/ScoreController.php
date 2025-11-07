<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
    #[Route('/scores', name: 'app_scores')]
    public function index(ScoreRepository $scoreRepository): Response
    {
        $scores = $scoreRepository->findBy([], ['score' => 'DESC'], 20);

        return $this->render('score/index.html.twig', [
            'scores' => $scores,
        ]);
    }
}
