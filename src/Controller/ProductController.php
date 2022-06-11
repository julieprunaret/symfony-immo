<?php

namespace App\Controller;

use App\Entity\Biens;
use DateTimeImmutable;
use App\Form\ProductType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/bien/{id}', name: 'app_product')]
    public function read($id, ManagerRegistry $doctrine): Response
    {
        $bien = $doctrine->getRepository(Biens::class)->find($id);
        return $this->render('product/details_product.html.twig', [
            'bien' => $bien
        ]);
    }

    //create
    #[Route('/ajouter/bien', name: 'add_product')]
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        // On instancie notre objet produit
        $bien = new Biens();
        // On initialise nos champs dates avec la date d'aujourd'hui 
        $bien->setCreatedAt(new DateTimeImmutable());

        // Etape 01 : Crée une instance de la classe Form à partir de la classe Products
        $formBien = $this->createForm(ProductType::class, $bien);
        // Etape 02 : Permet de gérer le traitement de la saisie du formulaire.
        $formBien->handleRequest($request);
        // Etape 03 : test si le formulaire a été saisi et si les règles de validations sont vérifiées
        if ($formBien->isSubmitted() && $formBien->isValid()) {
            // Etape 3.1 : On demande à doctrine de surveiller / gérer l'objet en cours
            $entityManager = $doctrine->getManager();
            // Etape 3.2 : On envoi les données a la bdd
            $entityManager->persist($bien);
            $entityManager->flush(); //on envoit de nouvelles données
            // Etape 3.3 : Affichage d'un message succès
            $this->addFlash(
                'success_add',
                'Votre bien' . $bien->getTitle() . 'a bien été ajouté !'
            );
            // Etape 3.4 : redirection
            return $this->redirectToRoute('app_home');
        }

        //thank you @LiseRochat for all amazing comments
        // On envois la page avec le formulaire et on permet la création de la vue (qu'on appellera dans le template)
        return $this->render('product/form_product.html.twig', [
            'formBien' => $formBien->createView(),
            'formTitle' => 'Ajouter un bien',
            'formSubmitLabel'=> 'Ajouter',
        ]);
    }

    //Update
    #[Route('/modifier/{id}', name: 'update_product')]
    public function update($id, Request $request, ManagerRegistry $doctrine)
    {
        // Etape 01 : on va récupérer l'objet concerné avec son id
        $bien = $doctrine->getRepository(Biens::class)->find($id);
        // On initialise nos champs dates avec la date d'aujourd'hui
        $bien->setUpdatedAt(new DateTimeImmutable());

        $formBien = $this->createForm(ProductType::class, $bien);
        $formBien->handleRequest($request);

        if ($formBien->isSubmitted() && $formBien->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            $this->addFlash(
                'success_edit',
                'Votre bien' . $bien->getTitle() . 'a bien été modifié !'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/form_product.html.twig', [
            'formBien' => $formBien->createView(),
            'formTitle' => 'Modifier un bien',
            'formSubmitLabel'=> 'Modifier',
        ]);
    }

    //Delete
    #[Route('/suprimer/{id}', name: 'delete_product')]
    public function delete($id, ManagerRegistry $doctrine) : RedirectResponse
    {
        // Etape 01 : on va récupérer l'objet concerné avec son id
        $bien = $doctrine->getRepository(Biens::class)->find($id);

        $entityManager = $doctrine->getManager();
        $entityManager->remove($bien);
        $entityManager->flush();

        $this->addFlash(
            'success_delete',
            'Votre bien' . $bien->getTitle() . 'a bien été suprimé !'
        );

        return $this->redirectToRoute('app_home');
    }
}
