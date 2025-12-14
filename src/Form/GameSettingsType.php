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
                'label' => 'Vitesse de la pince',
                'scale' => 1,
                'attr' => [
                    'min' => 1.0,
                    'max' => 10.0,
                    'step' => 0.5,
                    'placeholder' => '5.0'
                ],
                'help' => 'Vitesse de déplacement de la pince (1.0 = lent, 10.0 = rapide)'
            ])
            ->add('timeLimit', NumberType::class, [
                'label' => 'Temps limite (secondes)',
                'attr' => [
                    'min' => 30,
                    'max' => 300,
                    'step' => 10,
                    'placeholder' => '60'
                ],
                'help' => 'Note : Le jeu utilise un système d\'énergie, ce paramètre est optionnel'
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté',
                'choices' => [
                    'Facile - Idéal pour débutants' => 'Easy',
                    'Moyen - Équilibré' => 'Medium',
                    'Difficile - Pour experts' => 'Hard',
                ],
                'help' => 'Affecte la force de préhension de la pince'
            ])
            ->add('itemSpawnRate', NumberType::class, [
                'label' => 'Fréquence d\'apparition des items',
                'scale' => 1,
                'attr' => [
                    'min' => 0.5,
                    'max' => 5.0,
                    'step' => 0.5,
                    'placeholder' => '2.5'
                ],
                'help' => 'Nombre d\'items apparaissant par seconde (0.5 = rare, 5.0 = fréquent)'
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
