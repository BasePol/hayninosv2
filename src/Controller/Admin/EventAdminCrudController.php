<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use App\Entity\Comentario;

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

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class EventAdminCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }

   
    public function configureActions(Actions $actions): Actions
    {

        
        $user = $this->getUser();
        
        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
        ->linkToCrudAction('addToFavorites');

        // $goToStripe = Action::new('goToStripe')
        // ->linkToUrl('https://www.stripe.com/')
        // ->createAsGlobalAction();


        if($user == null){
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->addBatchAction(Action::new('approve', 'Add to Favorites')
            ->linkToCrudAction('addToFavorites')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'))
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
        }
        else
        {
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
           
        }




    }

    // protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    // {
    // $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'];

    // if (Action::SAVE_AND_RETURN === $submitButtonName) {
    //     $comentario = new Comentario();
    //     $comentario = t

    // }

    // return parent::getRedirectResponseAfterSave($context, $action);
    // }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('titulo'),
            ImageField::new('image')
            ->setBasePath('img/')
            ->setUploadDir('public/img/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),   
            TextEditorField::new('descripcion'),
            DateField::new('fechaInicio'),
            DateField::new('fechaFin'),
            TextField::new('tipo_publico'),
            TextField::new('localidad'),
            TextField::new('direccion')->hideOnIndex(),
            NumberField::new('precio'),
            NumberField::new('rating'),
            CollectionField::new('comentarios')->onlyOnDetail(),
            //->setCrudController(ComentarioCrudController::class)->autocomplete(),
           // ->renderExpanded()
           // ->setEntryIsComplex(),
            
            //->setTemplatePath('consultant/evento_comentario.html.twig'),


        ];
    }
    
}

