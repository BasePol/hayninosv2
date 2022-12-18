<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CalendarController;
use App\Entity\Project;
use App\Entity\User;
use App\Entity\Relation;
use App\Entity\Calendar;
use App\Entity\AssignProject;
use App\Entity\HoursOfTheProject;
use \Datetime;
use App\Entity\ProjectManagment;
use App\Entity\Tasks;
use App\Entity\WeeksDates;
use App;
use App\Entity\BackgroundEventsConsultants;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserAskingForExtraHours;
use App\Repository\UserAskingForExtraHoursRepository;
use App\Repository\UserRepository;
use App\Entity\Justify;
use App\Repository\JustifyRepository;
use App\Entity\Notification;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class AdminDashboardController extends AbstractDashboardController
{
    protected EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager, UserRepository $user1, ProjectRepository $project ,AdminUrlGenerator $adminUrlGenerator) {
        $this->project = $project;
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

        $time = new \DateTime("now");
       
        $projects = $this->project->findAll();
        $finished = $this->project->counter()['TOTAL'];
        $totalProjects = $this->project->counterTotal()['TOTAL'];
        $totalCurrentProjects = $this->project->counterValid()['TOTAL'];
        $totalUsers = $this->user1->counterTotal()['TOTAL'];
        
            
        return $this->render('admin/index.html.twig', ['project' => $projects, 'time' => $time, 'finished' => $finished,'totalProjects' => $totalProjects, 'totalUsers' => $totalUsers, 'currentProjects' => $totalCurrentProjects] );

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
