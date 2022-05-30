<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $contactForm = $this->createForm(ContactType::class);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();
            $email = (new TemplatedEmail())
                ->from(new Address($contact['email'], $contact['firstName'] . ' ' . $contact['lastName']))
                ->replyTo(new Address($contact['email'], $contact['firstName'] . ' ' . $contact['lastName']))
                ->to(new Address('david.hurtrel@gmail.com'))
                ->subject('JDS - demande de contact - ' . $contact['subject'])
                ->htmlTemplate('contact/contact_email.html.twig')
                ->context([
                    'firstName' => $contact['firstName'],
                    'lastName' => $contact['lastName'],
                    'emailAddress' => $contact['email'],
                    'subject' => $contact['subject'],
                    'message' => $contact['message']
                ]);
            if ($contact['attachment'] !== null) {
                $originalFileName = pathinfo($contact['attachment']->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '.' . $contact['attachment']->guessExtension();
                $email->attachFromPath($contact['attachment']->getPathName(), $newFileName);
            }
            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
            return $this->redirectToRoute('contact');
        }
        
        return $this->render('contact/index.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}
