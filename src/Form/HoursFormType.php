<?php

namespace App\Form;

use App\Entity\HoursOfProject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use App\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HoursFormType extends AbstractType
{
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add ('projectName', EntityType::class, [
                'class'=>Project::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                    ->where('e.id IN (:id)')
                    ->setParameter('id', ($this->user->getProjects()->toArray()));
                },
                'choice_value' => function (?Project $entity) {
                    return $entity ? $entity->getNameproject() : '';
                }
            ])
            ->add('nameConsultant', EntityType::class, [
                'class'=>User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                    ->where('e.id = (:id)')
                    ->setParameter('id', ($this->user->getId()));
                },
            ])
            
            ->add('nHours', IntegerType::class, array('attr' => array('min' => '1', 'max'=>'8')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HoursOfProject::class,
        ]);
    }
}
