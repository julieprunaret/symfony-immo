<?php

namespace App\Controller;

use App\Entity\Status;
use App\Form\StatusType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatusController extends AbstractController
{
    #[Route('/status', name: 'app_status')]
    public function read(ManagerRegistry $doctrine): Response
    {
        $status = $doctrine->getRepository(Status::class)->findBy([], ['id' => 'DESC']);
        return $this->render('status/status.html.twig', [
            'status' => $status,
        ]);
    }



    //create
    #[Route('/ajouter/statut', name: 'app_statut_add')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $statut = new Status();

        $formStatut = $this->createForm(StatusType::class, $statut);
        $formStatut->handleRequest($request);
        if ($formStatut->isSubmitted() && $formStatut->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($statut);
            $entityManager->flush();
            $this->addFlash(
                'success_add_statut',
                'Votre statut' . $statut->getName() . 'a bien été ajouté !'
            );
            return $this->redirectToRoute('app_status');
        }

        return $this->render('status/statutform.html.twig', [
            'formStatut' => $formStatut->createView(),
            'formTitle' => 'Ajouter un statut',
            'formSubmitLabel' => 'Ajouter',
        ]);
    }

    //update
    #[Route('/modifier/statut/{id}', name: 'app_statut_edit')]
    public function edit($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $statut = $doctrine->getRepository(Status::class)->find($id);

        $formStatut = $this->createForm(StatusType::class, $statut);
        $formStatut->handleRequest($request);
        if ($formStatut->isSubmitted() && $formStatut->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash(
                'success_add_statut',
                'Votre statut' . $statut->getName() . 'a bien été modifié !'
            );
            return $this->redirectToRoute('app_status');
        }

        return $this->render('status/statutform.html.twig', [
            'formStatut' => $formStatut->createView(),
            'formTitle' => 'Modifier un statut',
            'formSubmitLabel' => 'Modifier',
        ]);
    }

    //Delete
    #[Route('/suprimer/statut/{id}', name: 'app_statut_delete')]
    public function delete($id, ManagerRegistry $doctrine): RedirectResponse
    {
        $statut = $doctrine->getRepository(Status::class)->find($id);

            $entityManager = $doctrine->getManager();
            $entityManager->remove($statut);
            $entityManager->flush();
            $this->addFlash(
                'success_delete_statut',
                'Votre statut' . $statut->getName() . 'a bien été suprimé !'
            );
            return $this->redirectToRoute('app_status');
    }
}
