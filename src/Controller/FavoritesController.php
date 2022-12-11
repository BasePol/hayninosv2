<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evento;
use App\Entity\RestaurantesBares;
use Doctrine\ORM\EntityManagerInterface;


class FavoritesController extends AbstractController
{

    protected EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/favorites', name: 'app_favorites')]
    public function index(EntityManagerInterface $repository, EntityManagerInterface $repository2): Response
    {

        $repository = $repository->getRepository(Evento::class);
        $events = $repository->findAll();
        /*
        $events = $repository->findBy(
            ['titulo' => 'prueba']
        );
        */
        $rdvs = [];

        foreach($events as $event){
            //if($event->getUsers($this->getUser()))
            if(in_array($this->getUser(),$event->getUsers()->getValues()))
            {
                $rdvs[] = [
                    'id' => $event->getId(),
                    'start' => $event->getFechaInicio()->format('Y-m-d'),
                    'end' => $event->getFechaFin()->format('Y-m-d'),
                    'title' => $event->getTitulo(),
                    'description' => $event->getDescripcion(),
                ];
            }
        }


        $repository2 = $repository2->getRepository(RestaurantesBares::class);
        $eventsRestaurants = $repository2->findAll();

        $rdvs2 = [];

        foreach($eventsRestaurants as $event){
            //if($event->getUsers($this->getUser()))
            if(in_array($this->getUser(),$event->getUsuarios()->getValues()))
            {
                $rdvs2[] = [
                    'id' => $event->getId(),
                    'nombre' => $event->getNombre(),
                    'descripcion' => $event->getDescripcion(),
                    'direccion' => $event->getDireccion(),
                    'precio' => $event->getPrecio(),
                ];
            }
        }


        $resultado = array_merge($rdvs, $rdvs2);

        //Array merge de los favoritos de uno y del otro



        $data = json_encode($rdvs);

        var_dump($data);



        return $this->render('favorites/index.html.twig',[
            'controller_name' => 'FavoritesController',
            'data' => $data,
        ]);
    }
}
