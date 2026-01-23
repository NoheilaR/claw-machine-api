<?php

namespace App\Command;

use App\Entity\GameSettings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-data',
    description: 'Initialise les données par défaut (GameSettings)',
)]
class InitDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Vérifier si GameSettings existe déjà
        $existing = $this->em->getRepository(GameSettings::class)->find(1);

        if ($existing) {
            $io->warning('GameSettings avec ID 1 existe déjà.');
            return Command::SUCCESS;
        }

        // Créer les settings par défaut
        $settings = new GameSettings();
        $settings->setTimeLimit(60);
        $settings->setEnergyTokenProbability(10.0);
        $settings->setMinEnergyTokens(1);
        $settings->setMaxEnergyTokens(3);
        $settings->setBombTokenProbability(5.0);
        $settings->setMinBombTokens(0);
        $settings->setMaxBombTokens(2);
        $settings->setBlackoutTokenProbability(3.0);
        $settings->setMinBlackoutTokens(0);
        $settings->setMaxBlackoutTokens(1);
        $settings->setInitialPlushieCount(15);
        $settings->setMinPlushiesBeforeRespawn(3);
        $settings->setMaxPlushiesInBin(20);
        $settings->setPlushiesPerSpawn(8);
        $settings->setCommonProbability(70.0);
        $settings->setRareProbability(30.0);
        $settings->setGlitchProbability(60.0);
        $settings->setGlitchEnergyThreshold(20.0);
        $settings->setGlitchChangeInterval(3.0);

        $this->em->persist($settings);
        $this->em->flush();

        $io->success('GameSettings créé avec succès !');

        return Command::SUCCESS;
    }
}
