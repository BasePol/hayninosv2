<?php

namespace App\Controller\Consultant;

use App\Entity\Comentario;
use App\Entity\Evento;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerTypeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;




class ComentarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comentario::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            
            AssociationField::new('evento', 'Eventos')->setTemplatePath('consultant/evento_image.html.twig'),
            TextField::new('mensaje'),
            ChoiceField::new('rating')->renderAsBadges([
                // $value => $badgeStyleName,
                'paid' => 'success',
                'pending' => 'warning',
                'refunded' => 'danger',
            ])
            ->setChoices([
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
            ])
        ];
    }


    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
       // if (Crud::NEW === $responseParameters->get('prueba')) {
        
           // $responseParameters->get('foo';
           //$responseParameters->set('foo', 'hooooola');

            var_dump($responseParameters->get('foo'));exit;

            // keys support the "dot notation", so you can get/set nested
            // values separating their parts with a dot:
   //         $responseParameters->setIfNotSet('bar.foo', '...');
            // this is equivalent to: $parameters['bar']['foo'] = '...'
       // }

        return $responseParameters;
    }


    public function createEntity(string $entityFqcn){
        $comentario = new Comentario();
        $time = new \DateTime();
        //var_dump($comentario->getEvento());exit;
        $comentario->setUser($this->getUser());
        $comentario->setCreatedAt($time);

        return $comentario;
    }
    
}
