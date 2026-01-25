<?php

namespace App\Controller;

use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ScoreRepository $scoreRepository): Response
    {
        $topScores = $scoreRepository->findBy([], ['score' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'topScores' => $topScores,
        ]);
    }
}
