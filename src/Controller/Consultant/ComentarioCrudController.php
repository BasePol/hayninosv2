<?php

namespace App\Controller\Consultant;

use App\Entity\Comentario;
use App\Entity\Evento;

use App\Form\EventoType;


use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerTypeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;

use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\CrudControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetsDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\InsufficientEntityPermissionException;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ActionFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\ControllerFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FormFactory;
use EasyCorp\Bundle\EasyAdminBundle\Factory\PaginatorFactory;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FiltersFormType;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\Model\FileUploadState;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityUpdater;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Provider\FieldProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Security\Permission;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class ComentarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }

    public function createEditQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {

        $response = parent::createEditQueryBuilder($searchDto,$entityDto,$fields,$filters);

            $response->where('entity.id = :id');
            $response->setParameter('id', $this->getUser()->getId());

        return $response;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('titulo')->setFormTypeOption('disabled','disabled'),
            TextField::new('user')->setFormTypeOption('disabled','disabled'),

           // DateField::new('time')->setFormat('yyyy.MM.dd')
            //->setFormTypeOption('disabled','disabled'),
            //->renderAsChoice(),
            CollectionField::new('comentarios')
            ->setEntryType(EventoType::class)
            ->allowDelete(false)
           // ->renderExpanded()
            ->setEntryIsComplex(true)
            ->showEntryLabel(),
            //->setEntryType(Comentario::class)
           // ->setCrudController(ComentarioCrudController::class)
            //->renderExpanded()
            //->setEntryIsComplex()
           // ->setTemplatePath('consultant/evento_comentario.html.twig'),
            //CollectionField::new('comentarios')

        ];
    }


    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
       // if (Crud::NEW === $responseParameters->get('prueba')) {
        
           // $responseParameters->get('foo';
           //$responseParameters->set('foo', 'hooooola');

            //var_dump($responseParameters->all());exit;

            // keys support the "dot notation", so you can get/set nested
            // values separating their parts with a dot:
   //         $responseParameters->setIfNotSet('bar.foo', '...');
            // this is equivalent to: $parameters['bar']['foo'] = '...'
       // }

        return $responseParameters;
    }


/*
    public function createEntity(string $entityFqcn){
       // var_dump($this->getContext()->getEntity()->getInstance()->getMensaje());

       // $currentEntity = $this->getContext()->getEntity()->getInstance();
       
       $entityManager = $this->container->get('doctrine')->getManagerForClass(Evento::class);

       $evento = new Evento();
        $comentario = new Comentario();
        $time = new \DateTime();
        //var_dump($comentario->getEvento());exit;
        $comentario->setUser($this->getUser());
        $comentario->setCreatedAt($time);
        $comentario->setMensaje(" ");

        $evento->addComentarioObject($comentario);

        $entityManager->flush();
        $entityManager->persist($evento);

        
        return $evento;
    }
 */
    /*
    public function updateEntity($entity)
    {
        var_dump($entity);
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime());
        }

        parent::updateEntity($entity);
    }


    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setBlogPostSlug'],
        ];
    }

    public function setBlogPostSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Evento)) {
            return;
        }

        $slug = $this->slugger->slugify($entity->getTitle());
        $entity->setSlug($slug);
    }
    */   

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \LogicException('Currently logged in user is not an instance of User?!');
        }

        $comentarios = $entityInstance->getComentarios();
        $lastIndex = count($comentarios)-1 ;
        $entityInstance->getComentarios()[$lastIndex]->setUser($user);
        $total = 0;
        foreach($comentarios as $comentario)
        {
            $total += $comentario->getRatings();
        }

        if ($lastIndex === 0){
            var_dump("aqui");exit;
            $entityInstance->setRating($total);
        }
        else{
            var_dump("oooo aqui");exit;

            $entityInstance->setRating($total/$lastIndex);
        }
        parent::updateEntity($entityManager, $entityInstance);
    }   
}
