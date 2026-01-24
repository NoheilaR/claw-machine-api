<?php

namespace App\Controller;

use App\Entity\GameSettings;
use App\Form\GameSettingsType;
use App\Repository\GameSettingsRepository;
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
    public function dashboard(StatsService $statsService): Response
    {
        $globalStats = $statsService->calculateGlobalStats();
        $topPlayers = $statsService->getTopPlayers(3);
        $recentScores = $statsService->getRecentScores(5);
        $todayStats = $statsService->getTodayStats();

        return $this->render('admin/dashboard.html.twig', [
            'globalStats' => $globalStats,
            'topPlayers' => $topPlayers,
            'recentScores' => $recentScores,
            'todayStats' => $todayStats,
        ]);
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
