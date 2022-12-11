<?php

namespace App\Form;

use App\Entity\HoursOfProject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoursOfProject1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nHours')
            ->add('start')
            ->add('end')
            ->add('createdAt')
            ->add('paymentPerHour')
            ->add('total')
            ->add('projectName')
            ->add('nameConsultant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HoursOfProject::class,
        ]);
    }
}
