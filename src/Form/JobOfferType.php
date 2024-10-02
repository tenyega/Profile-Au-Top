<?php

namespace App\Form;

use App\Entity\JobOffer;
use App\Entity\User;
use App\Enum\JobStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('company')
            ->add('link')
            ->add('location')
            ->add('salary')
            ->add('contactPerson')
            ->add('contactEmail')
            ->add('applicationDate', null, [
                'widget' => 'single_text',
            ])
            ->add('app_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastName',
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
