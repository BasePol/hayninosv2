<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EventoRepository;

class MenuController extends AbstractController
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager, EventoRepository $evento)
    {
        $this->urlGenerator = $urlGenerator;
        $this->evento = $evento;
    }

    #[Route('/menu', name: 'app_menu')]
    public function index(): Response
    {
        $roles = $this->getUser()->getRoles();
        //var_dump($roles); exit;

        if ($roles[0] == "ROLE_ADMIN") {
            return new RedirectResponse($this->urlGenerator->generate('admin'));
        }
        else{
            return new RedirectResponse($this->urlGenerator->generate('consultant'));
        }
    }
}
