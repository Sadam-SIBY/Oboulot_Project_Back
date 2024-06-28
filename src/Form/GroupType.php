<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use App\Entity\Exercise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la classe',
            ])
            ->add('level', TextType::class, [
                'label' => 'Niveau de la classe',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
            ])
            ->add('user', EntityType::class, [
                'label' => 'Elèves',
                'class' => User::class,
                'choice_label' =>  function (User $user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                    },
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
