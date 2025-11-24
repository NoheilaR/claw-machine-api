<?php

namespace App\Form;

use App\Entity\GameSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GameSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clawSpeed', NumberType::class, [
                'label' => 'Vitesse de la griffe',
                'scale' => 2,
            ])
            ->add('timeLimit', NumberType::class, [
                'label' => 'Temps initial (secondes)',
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Facile' => 'easy',
                    'Moyen' => 'medium',
                    'Difficile' => 'hard',
                ],
            ])
            ->add('itemSpawnRate', NumberType::class, [
                'label' => 'Taux de spawn des items',
                'scale' => 2,
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameSettings::class,
        ]);
    }
}
