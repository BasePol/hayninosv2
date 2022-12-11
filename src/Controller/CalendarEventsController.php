<?php

namespace App\Controller;
;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Evento;
use Doctrine\ORM\EntityManagerInterface;



class CalendarEventsController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/calendar/events', name: 'app_calendar_events')]
    public function index(EntityManagerInterface $repository): Response
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
        $data = json_encode($rdvs);

        //var_dump($data);exit;

        return $this->render('calendar_events/index.html.twig',[ 'data' => $data,]);

        /*
        return $this->render('calendar/indexUsers.html.twig',['bucleDias' => count($block)-1,'blockDays' => array($block),'time' => $time->format('Y-m-d') , 'y' => array($y),'bucle' => $weeks->findAll(), 'j' => $j,'i' => $i, 'start' => array($dataWeeksStart), 'end' => array($dataWeeksEnd), 'tokenHoliday' => $tokenHoliday,'token' => $token , 'data' => $data, 'bool' => $bool, 'dataWeeksStart' => $data1, 'dataWeeksEnd' => $data2  ,'hoursForm' => $form->createView(), 'holidayForm' => $form1->createView()]);
*/

        return $this->render('calendar_events/index.html.twig', [
            'controller_name' => 'CalendarEventsController',
        ]);
    }
}
