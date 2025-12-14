<?php

namespace App\Controller;

use App\Entity\GameSettings;
use App\Form\GameSettingsType;
use App\Repository\GameSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function index(
        Request $request,
        GameSettingsRepository $settingsRepo,
        EntityManagerInterface $em
    ): Response
    {
        $settings = $settingsRepo->find(1);

        if (!$settings) {
            $settings = new GameSettings();
            // Valeurs par défaut cohérentes avec le jeu Unity
            $settings->setClawSpeed(5.0);      // Vitesse de la pince (1.0 - 10.0)
            $settings->setTimeLimit(60);        // Temps limite en secondes (pas utilisé avec le système d'énergie)
            $settings->setDifficulty('Medium'); // Easy, Medium, Hard
            $settings->setItemSpawnRate(2.5);   // Items par seconde (0.5 - 5.0)
            $em->persist($settings);
            $em->flush();
        }

        $form = $this->createForm(GameSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Paramètres sauvegardés !');
            return $this->redirectToRoute('app_settings');
        }

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
            'settings' => $settings,
        ]);
    }
}
