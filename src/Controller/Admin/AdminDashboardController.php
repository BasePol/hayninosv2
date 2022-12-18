<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Relation;
use App\Entity\Calendar;
use \Datetime;
use App\Entity\Tasks;
use App;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;

use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class AdminDashboardController extends AbstractDashboardController
{
    protected EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager, UserRepository $user1,AdminUrlGenerator $adminUrlGenerator) {
        $this->entityManager = $entityManager;
        $this->user1 = $user1;
        $this->adminUrlGenerator = $adminUrlGenerator;

     
    } 
    private $adminUrlGenerator;

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(EventAdminCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

    return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ikids Admin World');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Basic');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Importar Eventos', 'fa fa-hourglass', Evento::class)
        ->setController(EventoCrudController::class);
        yield MenuItem::linkToCrud('Lugares', 'fa fa-hourglass', Evento::class)
        ->setController(RestaurantesBaresAdminCrudController::class);
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-hourglass', User::class)
        ->setController(UserCrudController::class);
   
    }
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
        ->setName($user->getUsername())
        ->displayUserName(true)
        ->addMenuItems([
            //MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
            //MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
            MenuItem::section(),
            //MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
        ]);
    }
}
