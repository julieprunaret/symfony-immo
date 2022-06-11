<?php

namespace App\Controller;

use App\Entity\Biens;
use DateTimeImmutable;
use App\Form\ProductType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $bien = new Biens();
        $bien->setCreatedAt(new DateTimeImmutable());

        $formBien = $this->createForm(ProductType::class, $bien);
        $formBien->handleRequest($request); //gère le traitement du formulaire

        if($formBien->isSubmitted() && $formBien->isValid())
        {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($bien);//on enregistre de nouvelles données
            $entityManager->flush();//on envoit de nouvelles données

            $this->addFlash(
                'add_sucess',
                'Votre bien' . $bien->getTitle(). 'a bien été ajouté !'
            );

            return $this->redirectToRoute('app_home');
        }


        return $this->render('product/form_product.html.twig', [
            'formBien' => $formBien->createView()
        ]);
    }


}
