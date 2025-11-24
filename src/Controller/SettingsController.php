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
            $settings->setClawSpeed(1.0);
            $settings->setTimeLimit(30);
            $settings->setDifficulty('medium');
            $settings->setItemSpawnRate(0.5);
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
