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
            // ===== TOKENS - ENERGY =====
            ->add('energyTokenProbability', NumberType::class, [
                'label' => 'Probabilite Energy Token (%)',
                'scale' => 1,
                'attr' => [
                    'step' => 1,
                    'placeholder' => '10'
                ],
                'help' => 'Recommande: 5-15%. Probabilite qu\'un energy token apparaisse'
            ])
            ->add('minEnergyTokens', IntegerType::class, [
                'label' => 'Min Energy Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 1-3. Nombre minimum d\'energy tokens garantis'
            ])
            ->add('maxEnergyTokens', IntegerType::class, [
                'label' => 'Max Energy Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 3-6. Nombre maximum d\'energy tokens'
            ])

            // ===== TOKENS - BOMB =====
            ->add('bombTokenProbability', NumberType::class, [
                'label' => 'Probabilite Bomb Token (%)',
                'scale' => 1,
                'attr' => [
                    'step' => 1,
                    'placeholder' => '5'
                ],
                'help' => 'Recommande: 3-10%. Probabilite qu\'une bombe apparaisse'
            ])
            ->add('minBombTokens', IntegerType::class, [
                'label' => 'Min Bomb Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 0. Nombre minimum de bombes'
            ])
            ->add('maxBombTokens', IntegerType::class, [
                'label' => 'Max Bomb Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 2-3. Nombre maximum de bombes'
            ])

            // ===== TOKENS - BLACKOUT =====
            ->add('blackoutTokenProbability', NumberType::class, [
                'label' => 'Probabilite Blackout Token (%)',
                'scale' => 1,
                'attr' => [
                    'step' => 1,
                    'placeholder' => '3'
                ],
                'help' => 'Recommande: 2-5%. Probabilite qu\'un blackout apparaisse'
            ])
            ->add('minBlackoutTokens', IntegerType::class, [
                'label' => 'Min Blackout Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 0. Nombre minimum de blackouts'
            ])
            ->add('maxBlackoutTokens', IntegerType::class, [
                'label' => 'Max Blackout Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 1-2. Nombre maximum de blackouts'
            ])

            // ===== TOKENS - STUN =====
            ->add('stunTokenProbability', NumberType::class, [
                'label' => 'Probabilite Stun Token (%)',
                'scale' => 1,
                'attr' => [
                    'step' => 1,
                    'placeholder' => '5'
                ],
                'help' => 'Recommande: 3-8%. Probabilite qu\'un stun apparaisse'
            ])
            ->add('minStunTokens', IntegerType::class, [
                'label' => 'Min Stun Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 0. Nombre minimum de stuns'
            ])
            ->add('maxStunTokens', IntegerType::class, [
                'label' => 'Max Stun Tokens',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 1-3. Nombre maximum de stuns'
            ])
            ->add('stunDuration', NumberType::class, [
                'label' => 'Duree du Stun (secondes)',
                'scale' => 1,
                'attr' => [
                    'step' => 0.5,
                    'placeholder' => '2'
                ],
                'help' => 'Recommande: 1.5-3s. Duree d\'immobilisation du joueur'
            ])

            // ===== PELUCHES - QUANTITÉS =====
            ->add('initialPlushieCount', IntegerType::class, [
                'label' => 'Peluches initiales',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 10-20. Nombre de peluches au demarrage'
            ])
            ->add('minPlushiesBeforeRespawn', IntegerType::class, [
                'label' => 'Seuil de respawn',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 3-5. Nombre minimum avant respawn'
            ])
            ->add('maxPlushiesInBin', IntegerType::class, [
                'label' => 'Max peluches dans le bac',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 20-30. Nombre maximum dans le bac'
            ])
            ->add('plushiesPerSpawn', IntegerType::class, [
                'label' => 'Peluches par vague',
                'attr' => ['step' => 1],
                'help' => 'Recommande: 3-8. Peluches spawnees par vague'
            ])

            // ===== PELUCHES - RARETÉS =====
            ->add('commonProbability', NumberType::class, [
                'label' => 'Probabilite Common (%)',
                'scale' => 1,
                'attr' => ['step' => 5],
                'help' => 'Recommande: 70-80%. Total common+rare doit = 100%'
            ])
            ->add('rareProbability', NumberType::class, [
                'label' => 'Probabilite Rare (%)',
                'scale' => 1,
                'attr' => ['step' => 5],
                'help' => 'Recommande: 20-30%. Probabilite peluche rare'
            ])
            // ===== PERTURBATION COMMANDES (GLITCH) =====
            ->add('glitchProbability', NumberType::class, [
                'label' => 'Probabilite inversion commandes (%)',
                'scale' => 1,
                'attr' => ['step' => 5],
                'help' => 'Recommande: 30-50%. Probabilite que les commandes s\'inversent quand energie basse'
            ])
            ->add('glitchEnergyThreshold', NumberType::class, [
                'label' => 'Seuil energie pour glitch (%)',
                'scale' => 1,
                'attr' => ['step' => 5],
                'help' => 'Recommande: 20-30%. Seuil d\'energie pour activer le glitch'
            ])
            ->add('glitchChangeInterval', NumberType::class, [
                'label' => 'Intervalle changement glitch (s)',
                'scale' => 1,
                'attr' => ['step' => 0.5],
                'help' => 'Recommande: 2-5s. Intervalle entre chaque changement de glitch'
            ])

            ->add('save', SubmitType::class, ['label' => 'Sauvegarder les parametres']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameSettings::class,
        ]);
    }
}
