<?php

namespace App\Controller\Admin;

use App\Entity\Evento;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;

use Symfony\Contracts\HttpClient\HttpClientInterface;

use Doctrine\ORM\EntityManagerInterface;



class EventoCrudController extends AbstractCrudController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    public static function getEntityFqcn(): string
    {
        return Evento::class;
    }


        
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titulo'),
            TextEditorField::new('descripcion'),
            ImageField::new('image'),
        ];
    }


    public function importMadridEvents(EntityManagerInterface $entityManager)
    {

        $response = $this->client->request(
            'GET',
            //'https://api.github.com/repos/symfony/symfony-docs'
            'https://datos.madrid.es/egob/catalogo/206974-0-agenda-eventos-culturales-100.json'
        );

        //exit;
        //https://api.github.com/repos/symfony/symfony-docs

        //https://datos.madrid.es/egob/catalogo/206974-0-agenda-eventos-culturales-100.json

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

       // var_dump($content['@graph'][0]['title']);exit;

        foreach ($content['@graph'] as $eventoJSON)
        {
            $evento = new Evento();
            $evento->setTitulo($eventoJSON['title']);
            $evento->setDescripcion($eventoJSON['description']);
            $evento->setPrecio((float)$eventoJSON['price']);
//$fechaInicioJson = new \DateTime($eventoJSON['dtstart']);
            //var_dump((string)$eventoJSON['location']['latitude'] ."," . (string)$eventoJSON['location']['longitude']);exit;
            //var_dump(date('Y-m-d h:i:s', strtotime($eventoJSON['dtstart'])));
    //        var_dump(\DateTime::createFromFormat('Y-m-d', $eventoJSON['dtstart']));exit;
            $evento->setFechaInicio(new \DateTime($eventoJSON['dtstart']));
            $evento->setFechaFin(new \DateTime($eventoJSON['dtend']));
            $evento->setTipoPublico($eventoJSON['audience']);
            $evento->setLocalidad($eventoJSON['event-location']);
            if($eventoJSON['location'] ?? NULL){
                //$evento->setDireccion('https://maps.google.com/?ll='.(string)$eventoJSON['location']['latitude'] ."," . (string)$eventoJSON['location']['longitude']);
                $evento->setDireccion('https://maps.google.com/?q='.$eventoJSON['event-location']);
            
            }
            else
            {
                $evento->setDireccion($eventoJSON['event-location']);
            }
            $evento->setImage('KidFront.png');
            //$evento->setLocalidad($eventoJSON['address']['area']['locality']);
            //$evento->setDireccion($eventoJSON['address']['area']['street-address']);


      //      $evento->setFechaFin( \DateTime::createFromFormat('Y-m-d', $eventoJSON['dtend'])->format('Y-m-d'));
            //$evento->setFechaFin($eventoJSON['dtend']);
            if ($eventoJSON['audience']=="Familias"){
            $entityManager->flush();
            $entityManager->persist($evento);}
            //break;
        }
        exit;
    }

    public function addToFavorites(BatchActionDto $batchActionDto)
    {
        //$order = $context->getEntity()->getInstance();

        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        //var_dump($entityManager);exit;
        $entityManager->addUser($this->$this->getUser());
        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());

    }

    public function configureActions(Actions $actions): Actions
    {
        $user = $this->getUser();
        
        $viewInvoice = Action::new('viewInvoice', 'Invoice', 'fa fa-file-invoice');


        $importMadridEvents = Action::new('importMadridEvents')
        ->linkToCrudAction('importMadridEvents')
        ->createAsGlobalAction();


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
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->addBatchAction(Action::new('approve', 'Add to Favorites')
            ->linkToCrudAction('addToFavorites')
            ->addCssClass('btn btn-primary')
            ->setIcon('fa fa-user-check'))
            

        
            ->add(Crud::PAGE_INDEX, $importMadridEvents);
            //->remove(Crud::PAGE_INDEX, Action::DELETE)
            //->remove(Crud::PAGE_INDEX, Action::NEW);
        }
    
}
}