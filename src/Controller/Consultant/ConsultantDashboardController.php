<?php

namespace App\Controller\Consultant;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Justify;
use App\Entity\Tasks;
use App\Controller\CalendarEventsController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use App\Repository\EventoRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

class ConsultantDashboardController extends AbstractDashboardController
{
    protected EntityManagerInterface $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, EventoRepository $evento,AdminUrlGenerator $adminUrlGenerator) {
        $this->evento = $evento;
        $this->adminUrlGenerator = $adminUrlGenerator;
    } 

    private $adminUrlGenerator;

    public function configureAssets(): Assets
    {
        return Assets::new()->addCssFile('css/admin2.css');
    }

    #[Route('/consultant', name: 'consultant')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(EventoConsultantCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

    return $this->redirect($url);
      
        //return $this->render('consultant/index.html.twig',[]);
    }
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ikids - Dashboard de Actividades');
    }
    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Main');
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Home', 'fa fa-home', 'app_start');
        yield MenuItem::linkToRoute('Login', 'fa fa-id-card', 'app_login');
        //->setPermission('IS_ANONYMOUS');

        yield MenuItem::section('Services');
        yield MenuItem::linkToCrud('Eventos', 'fa fa-child', Evento::class)
        ->setController(EventoConsultantCrudController::class);
        yield MenuItem::linkToRoute('Calendario de Eventos', 'fa fa-calendar-check-o', 'app_calendar_events')
        ->setCssClass('text-white')
        ->setPermission('ROLE_CONSULTANT');
        yield MenuItem::linkToCrud('Restaurantes and Bares', 'fa fa-spoon', RestaurantesBares::class)
        ->setController(RestaurantesBaresConsultantCrudController::class);
        yield MenuItem::linkToCrud('Favoritos', 'fa fa-heart', User::class)
        ->setController(UserCrudController::class)
        ->setPermission('ROLE_CONSULTANT');
        
        //Desactivar que se pueda llegar directamente
        
        yield MenuItem::linkToCrud('Comentarios', 'fa fa-spoon', Comentario::class)
        ->setController(ComentarioCrudController::class)
        ->setPermission('ROLE_CONSULTANT');
        
        
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

           // MenuItem::linkToLogout('Logout', 'fa fa-sign-out')
        ]);
    }
}