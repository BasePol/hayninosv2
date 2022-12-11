<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WeeksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('start', DateType::class, [ 
            'widget' => 'single_text',
                      'html5' => false,
                      'attr' => ['class' => 'js-datepicker'],
        ])
        ->add('end', DateType::class, [ 
            'widget' => 'single_text',
                      'html5' => false,
                      'attr' => ['class' => 'js-datepicker'],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
