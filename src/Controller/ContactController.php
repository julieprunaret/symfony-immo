<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{

    #[Route('/contact', name: 'app_contact_form')]
    public function add(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer) : Response
    {
        $contact = new Contact();
        $contact->setCreatedAt(new DateTimeImmutable());

        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush(); 

            //Email
            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('contact@immo-agency.com')
            ->subject($contact->getObject())
            ->html($this->renderView('emails/contactemail.html.twig', [
                'contact' => $contact
            ]));

            $mailer->send($email);

            $this->addFlash(
                'success_message_contact',
                'Votre message ' . $contact->getObject() . ' a bien été envoyé !'
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
