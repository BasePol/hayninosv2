<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventoRepository;



class StartController extends AbstractController
{

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, EventoRepository $evento)
    {
        $this->urlGenerator = $urlGenerator;
        $this->evento = $evento;
    }


    #[Route('/eventos', name: 'app_eventos')]
    public function index2(): Response
    {

        $user = $this->getUser();
/*
        if ($user == null){
            $user = new User();
            $user->setUsername("ANON");
            //this->setUser($user);
        }

        //$user = $this->getUser()->getRoles();

        var_dump($user); exit;
  */      
        return new RedirectResponse($this->urlGenerator->generate('consultant'));



        return $this->render('start/index.html.twig', [
            'controller_name' => 'EventoCrudController',
        ]);
    }


    #[Route('/', name: 'app_start')]
    public function index(): Response
    {
        return $this->render('start/index.html.twig', [
            'controller_name' => 'StartController',
        ]);
    }
}
