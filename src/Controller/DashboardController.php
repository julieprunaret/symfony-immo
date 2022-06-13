<?php

namespace App\Controller;

use App\Entity\Biens;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_dashboard')]
    public function dashboard(ManagerRegistry $doctrine): Response
    {
        //ci dessous pour récupérer les infos du user provider, comme c'est un provider il n'y a pas besoin de faire de requete
        $user_connected = $this->getUser();
        $biens = $doctrine->getRepository(Biens::class)->findAll();
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'biens' =>$biens
        ]);
    }
}
