<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantesBares;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;


class RestaurantesBaresAdminCrudController extends AbstractCrudController
{   
    public static function getEntityFqcn(): string
    {
        return RestaurantesBares::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        $user = $this->getUser();
        
        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
        ->linkToCrudAction('addToFavorites');


        if($user == null){
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
        }
        else
        {
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->addBatchAction(Action::new('approve', 'Add to Favorites')
            ->linkToCrudAction('addToFavorites')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'));
        }


    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('nombre'),
            ImageField::new('image')
            ->setBasePath('img/')
            ->setUploadDir('public/img/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),   
            TextEditorField::new('descripcion'),
            TextField::new('direccion'),
            TextField::new('precio'),

            //Field::new('user')->onlyWhenUpdating(),


        ];
    }
    
}

