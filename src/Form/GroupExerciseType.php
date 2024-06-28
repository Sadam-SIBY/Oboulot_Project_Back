<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Exercise;
use App\Entity\GroupExercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('group', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
                'label' => 'Nom de la classe',
                'placeholder' => '',
            ])
            ->add('exercise', EntityType::class, [
                'class' => Exercise::class,
                'choice_label' => 'title',
                'label' => 'Nom de l\'exercice',
                'placeholder' => ''
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Soumettre'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupExercise::class,
        ]);
    }
}
