<?php

namespace App\Controller\Consultant;

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
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;


use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;


class EventoConsultantCrudController extends AbstractCrudController
{

    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator){
        $this->adminUrlGenerator = $adminUrlGenerator;
    }


    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }


    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
       // if (Crud::NEW === $responseParameters->get('prueba')) {
        
            //$responseParameters->get('foo');


            // keys support the "dot notation", so you can get/set nested
            // values separating their parts with a dot:
   //         $responseParameters->setIfNotSet('bar.foo', '...');
            // this is equivalent to: $parameters['bar']['foo'] = '...'
       // }

        return $responseParameters;
    }

    public function addToFavorites(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        
        foreach ($batchActionDto->getEntityIds() as $id) {
            $evento = $entityManager->find($className, $id);
            //Validamos que no lo tenga en favoritos
            if (!$evento->getUsers()->contains($this->getUser()))
            {
                $evento->addUser($this->getUser());
            }
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }

    public function deleteFavorites(BatchActionDto $batchActionDto)
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        
        foreach ($batchActionDto->getEntityIds() as $id) {
            $evento = $entityManager->find($className, $id);
            //Validamos que lo tenga en favoritos
            if ($evento->getUsers()->contains($this->getUser()))
            {
                $evento->removeUser($this->getUser());
            }
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }


    public function cambiarDireccion(AdminContext $adminContext, AdminUrlGenerator $adminUrlGenerator)
    {
        $question = $adminContext->getEntity()->getInstance();
        //var_dump($question->getTitulo());exit;

        if (!$question instanceof Evento) {
            throw new \LogicException('Entity is missing or not a Question');
        }

                    //->setEntityId($question->getId())

       // var_dump($question->getTitulo());exit;

       $targetUrl = $adminUrlGenerator
            ->setDashboard(ConsultantDashboardController::class)
            ->setController(ComentarioCrudController::class)
            ->setAction(Action::EDIT)
            ->set('prueba', 'someValue')
            ->setEntityId($adminContext->getEntity()->getInstance()->getId())
            ->generateUrl();
        return $this->redirect($targetUrl);
    }


    public function configureActions(Actions $actions): Actions
    {

        $user = $this->getUser();
        
        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice')
        ->linkToCrudAction('addToFavorites');

        /*
        $url = $this->adminUrlGenerator
        ->setController(ComentarioCrudController::class)
        ->setDashboard(ConsultantDashboardController::class)
        ->setAction(Action::NEW)
        ->setEntityId($this->getEntityFqcn()->getId())
        //->set() example: ->set('foo', 'someValue')
        ->generateUrl();
        // $goToStripe = Action::new('goToStripe')
        // ->linkToUrl('https://www.stripe.com/')
        // ->createAsGlobalAction();
        */

        if($user == null){
            return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT);
        }
        else
        {
            return $actions

            ->add(Crud::PAGE_INDEX, Action::new('add-comment', "Añadir comentario")
            ->linkToCrudAction('cambiarDireccion'))
            //->linkToUrl($url))

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->addBatchAction(Action::new('approve', 'Add to Favorites')
            ->linkToCrudAction('addToFavorites')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'))


            ->addBatchAction(Action::new('deleteFavourites', 'Remove from Favorites')
            ->linkToCrudAction('deleteFavorites')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'));


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
            DateField::new('fechaInicio')->setFormat('yyyy.MM.dd')
            ->renderAsChoice(),
            DateField::new('fechaFin')->setFormat('yyyy.MM.dd')
            ->renderAsChoice(),
            TextField::new('tipo_publico'),
            TextField::new('localidad'),
            UrlField::new('direccion', null, array('label' => 'My New Label:')),
           // ->label("prueba"),
            //UrlField::new('direccion')->onlyOnDetail(),
            NumberField::new('precio'),
            NumberField::new('rating')->onlyOnDetail(),
            AssociationField::new('comentarios')->onlyOnDetail()
            ->setCrudController(ComentarioCrudController::class)->autocomplete()
            //->renderExpanded()
            //->setEntryIsComplex()
            ->setTemplatePath('consultant/evento_comentario.html.twig'),

            //CollectionField
          


        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add('localidad')
            ->add('precio')


        ;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
        ->setName($user->getUsername())
        ->displayUserName(true)
        ->addMenuItems([
            //MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
            MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
            MenuItem::section(),
            MenuItem::linkToRoute('Calendario de Eventos', 'fa fa-calendar-check-o', 'app_calendar_events')

            //MenuItem::linkToLogout('Logout', 'fa fa-sign-out')
        ]);
    }

    public function configureCrud(Crud $crud): Crud
{
    return $crud
        // the names of the Doctrine entity properties where the search is made on
        // (by default it looks for in all properties)
        //->setSearchFields(['name', 'description'])
        // use dots (e.g. 'seller.email') to search in Doctrine associations
        //->setSearchFields(['name', 'description', 'seller.email', 'seller.address.zipCode'])
        // set it to null to disable and hide the search box
        //->setSearchFields(null)
        // call this method to focus the search input automatically when loading the 'index' page
        //->setAutofocusSearch()

        // defines the initial sorting applied to the list of entities
        // (user can later change this sorting by clicking on the table columns)
        //->setDefaultSort(['id' => 'DESC'])
        //->setDefaultSort(['id' => 'DESC', 'title' => 'ASC', 'startsAt' => 'DESC'])
        // you can sort by Doctrine associations up to two levels
        //->setDefaultSort(['seller.name' => 'ASC'])

        // the max number of entities to display per page
        ->setPaginatorPageSize(5)
        // the number of pages to display on each side of the current page
        // e.g. if num pages = 35, current page = 7 and you set ->setPaginatorRangeSize(4)
        // the paginator displays: [Previous]  1 ... 3  4  5  6  [7]  8  9  10  11 ... 35  [Next]
        // set this number to 0 to display a simple "< Previous | Next >" pager
        ->setPaginatorRangeSize(4)

        // these are advanced options related to Doctrine Pagination
        // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
        ->setPaginatorUseOutputWalkers(true)
        ->setPaginatorFetchJoinCollection(true)
    ;
}
    
}

