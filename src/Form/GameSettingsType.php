<?php

namespace App\Form;

use App\Entity\GameSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ===== GÉNÉRAL =====
            ->add('timeLimit', IntegerType::class, [
                'label' => 'Temps limite (secondes)',
                'attr' => [
                    'min' => 30,
                    'max' => 300,
                    'step' => 10,
                    'placeholder' => '60'
                ],
                'help' => 'Note : Le jeu utilise un système d\'énergie, ce paramètre est optionnel'
            ])

            // ===== TOKENS - ENERGY =====
            ->add('energyTokenProbability', NumberType::class, [
                'label' => 'Probabilité Energy Token (%)',
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 30,
                    'step' => 1,
                    'placeholder' => '10'
                ],
                'help' => 'Probabilité qu\'un energy token apparaisse au lieu d\'une peluche (0-30%)'
            ])
            ->add('minEnergyTokens', IntegerType::class, [
                'label' => 'Min Energy Tokens',
                'attr' => ['min' => 0, 'max' => 5, 'step' => 1],
                'help' => 'Nombre minimum d\'energy tokens garantis dans le bac'
            ])
            ->add('maxEnergyTokens', IntegerType::class, [
                'label' => 'Max Energy Tokens',
                'attr' => ['min' => 1, 'max' => 10, 'step' => 1],
                'help' => 'Nombre maximum d\'energy tokens dans le bac'
            ])

            // ===== TOKENS - BOMB =====
            ->add('bombTokenProbability', NumberType::class, [
                'label' => 'Probabilité Bomb Token (%)',
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'placeholder' => '5'
                ],
                'help' => 'Probabilité qu\'une bombe apparaisse (0-20%)'
            ])
            ->add('minBombTokens', IntegerType::class, [
                'label' => 'Min Bomb Tokens',
                'attr' => ['min' => 0, 'max' => 3, 'step' => 1],
                'help' => 'Nombre minimum de bombes (recommandé: 0)'
            ])
            ->add('maxBombTokens', IntegerType::class, [
                'label' => 'Max Bomb Tokens',
                'attr' => ['min' => 0, 'max' => 5, 'step' => 1],
                'help' => 'Nombre maximum de bombes dans le bac'
            ])

            // ===== TOKENS - BLACKOUT =====
            ->add('blackoutTokenProbability', NumberType::class, [
                'label' => 'Probabilité Blackout Token (%)',
                'scale' => 1,
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 1,
                    'placeholder' => '3'
                ],
                'help' => 'Probabilité qu\'un blackout apparaisse (0-20%)'
            ])
            ->add('minBlackoutTokens', IntegerType::class, [
                'label' => 'Min Blackout Tokens',
                'attr' => ['min' => 0, 'max' => 2, 'step' => 1],
                'help' => 'Nombre minimum de blackouts (recommandé: 0)'
            ])
            ->add('maxBlackoutTokens', IntegerType::class, [
                'label' => 'Max Blackout Tokens',
                'attr' => ['min' => 0, 'max' => 3, 'step' => 1],
                'help' => 'Nombre maximum de blackouts (recommandé: 1)'
            ])

            // ===== PELUCHES - QUANTITÉS =====
            ->add('initialPlushieCount', IntegerType::class, [
                'label' => 'Peluches initiales',
                'attr' => ['min' => 5, 'max' => 30, 'step' => 1],
                'help' => 'Nombre de peluches au démarrage du jeu'
            ])
            ->add('minPlushiesBeforeRespawn', IntegerType::class, [
                'label' => 'Seuil de respawn',
                'attr' => ['min' => 1, 'max' => 10, 'step' => 1],
                'help' => 'Nombre minimum avant de respawner des peluches'
            ])
            ->add('maxPlushiesInBin', IntegerType::class, [
                'label' => 'Max peluches dans le bac',
                'attr' => ['min' => 10, 'max' => 50, 'step' => 1],
                'help' => 'Nombre maximum de peluches dans le bac'
            ])
            ->add('plushiesPerSpawn', IntegerType::class, [
                'label' => 'Peluches par vague',
                'attr' => ['min' => 1, 'max' => 15, 'step' => 1],
                'help' => 'Nombre de peluches spawnées par vague de respawn'
            ])

            // ===== PELUCHES - RARETÉS =====
            ->add('commonProbability', NumberType::class, [
                'label' => 'Probabilité Common (%)',
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 100, 'step' => 5],
                'help' => 'Probabilité peluche commune (total doit = 100%)'
            ])
            ->add('rareProbability', NumberType::class, [
                'label' => 'Probabilité Rare (%)',
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 100, 'step' => 5],
                'help' => 'Probabilité peluche rare'
            ])
            ->add('legendaryProbability', NumberType::class, [
                'label' => 'Probabilité Legendary (%)',
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 100, 'step' => 1],
                'help' => 'Probabilité peluche légendaire'
            ])

            ->add('save', SubmitType::class, ['label' => 'Sauvegarder les paramètres']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameSettings::class,
        ]);
    }
}
