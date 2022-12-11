<?php

namespace App\Controller;

use App\Entity\Calendar;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CalendarRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index()
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

    /**
     * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
     */
    public function majEvent(?Calendar $calendar, Request $request,  PersistenceManagerRegistry $doctrine)
    {
       
        $datos = json_decode($request->getContent());

        if(
            isset($datos->title) && !empty($datos->title) &&
            isset($datos->start) && !empty($datos->start) &&
            isset($datos->description) && !empty($datos->description) &&
            isset($datos->backgroundColor) && !empty($datos->backgroundColor) &&
            isset($datos->borderColor) && !empty($datos->borderColor) &&
            isset($datos->textColor) && !empty($datos->textColor)
        ){
         
            $code = 200;

         
            if(!$calendar){
               
                $calendar = new Calendar;

                $code = 201;
            }

            
            $calendar->setTitle($datos->title);
            $calendar->setDescription($datos->description);
            $calendar->setStart(new DateTime($datos->start));
            if($datos->allDay){
                $calendar->setEnd(new DateTime($datos->start));
            }else{
                $calendar->setEnd(new DateTime($datos->end));
            }
            $calendar->setAllDay($datos->allDay);
            $calendar->setBackgroundColor($datos->backgroundColor);
            $calendar->setBorderColor($datos->borderColor);
            $calendar->setTextColor($datos->textColor);

            $em = $doctrine ->getManager();
            $em->persist($calendar);
            $em->flush();

           
            return new Response('Ok', $code);
        }else{
          
            return new Response('Datos Incompletos', 404);
        }


        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}