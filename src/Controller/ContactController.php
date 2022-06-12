<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact_form')]
    public function add(Request $request, ManagerRegistry $doctrine) : Response
    {
        $contact = new Contact();
        $contact->setCreatedAt(new DateTimeImmutable());

        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush(); 
            $this->addFlash(
                'success_message_contact',
                'Votre message ' . $contact->getObject() . 'a bien été envoyé !'
            );
            return $this->redirectToRoute('app_contact_form');
        }

        return $this->render('contact/contactform.html.twig', [
            'formContact' => $formContact->createView(),
            'formTitle' => 'Nous contacter',
            'formSubmitLabel' => 'Envoyer le message'
        ]);
    }
}
