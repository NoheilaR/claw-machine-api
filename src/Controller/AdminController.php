<?php

namespace App\Controller;

use App\Entity\GameSettings;
use App\Entity\Score;
use App\Form\GameSettingsType;
use App\Repository\GameSettingsRepository;
use App\Repository\ScoreRepository;
use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    public function dashboard(StatsService $statsService, ScoreRepository $scoreRepository): Response
    {
        $globalStats = $statsService->calculateGlobalStats();
        $topPlayers = $statsService->getTopPlayers(3);
        $recentScores = $statsService->getRecentScores(5);
        $todayStats = $statsService->getTodayStats();
        $totalScores = $scoreRepository->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'globalStats' => $globalStats,
            'topPlayers' => $topPlayers,
            'recentScores' => $recentScores,
            'todayStats' => $todayStats,
            'totalScores' => $totalScores,
        ]);
    }

    #[Route('/reset-scores', name: 'admin_reset_scores', methods: ['POST'])]
    public function resetScores(Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier le token CSRF
        if (!$this->isCsrfTokenValid('reset-scores', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de securite invalide.');
            return $this->redirectToRoute('admin_dashboard');
        }

        // Supprimer tous les scores
        $connection = $em->getConnection();
        $connection->executeStatement('DELETE FROM score');

        // Réinitialiser l'auto-increment (optionnel)
        $connection->executeStatement('ALTER TABLE score AUTO_INCREMENT = 1');

        $this->addFlash('success', 'Tous les scores ont ete supprimes. Nouvelle saison !');
        return $this->redirectToRoute('admin_dashboard');
    }

    #[Route('/settings', name: 'admin_settings')]
    public function settings(
        Request $request,
        GameSettingsRepository $settingsRepo,
        EntityManagerInterface $em
    ): Response
    {
        $settings = $settingsRepo->find(1);

        if (!$settings) {
            $settings = new GameSettings();
            $settings->setTimeLimit(60);
            $em->persist($settings);
            $em->flush();
        }

        $form = $this->createForm(GameSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Parametres sauvegardes !');
            return $this->redirectToRoute('admin_settings');
        }

        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView(),
            'settings' => $settings,
        ]);
    }
}
