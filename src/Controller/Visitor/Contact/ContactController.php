<?php

namespace App\Controller\Visitor\Contact;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactFormType;
use App\Repository\SettingRepository;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'visitor_contact_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        SendEmailService $sendEmailService,
        SettingRepository $settingRepository
    ): Response
    {
        // 1- Créons l'instance du contact qui doit être ajouté en bdd
        $contact = new Contact();

        // 2- Créons le formulaire en se basant sur son type
        $form = $this->createForm(ContactFormType::class, $contact);

        // 4- Associer les données de la requête aux données du formulaire
        $form->handleRequest($request);

        // 5- Si le formulaire est soumis et que le formulaire est valide
        if ($form->isSubmitted() && $form->isValid())
        {

            // Initialiser les dates de création et de modification 
            $contact->setCreatedAt(new DateTimeImmutable());


            // Demander au manager des entités de préparer la requête d'insertion du nouveau contact en base de données
            $entityManager->persist($contact);

            // Exécuter la requête
            $entityManager->flush();

            // Envoyer le mail à l'admin pour prévenir du nouveau message
            $sendEmailService->send([
                "sender_email" => "quentin.martens@orange.fr",
                "sender_name" => "Padel Time",
                "recipient_email" => "quentin.martens@orange.fr",
                "subject" => "Nouveau message reçu",
                "html_template" => "emails/contact.html.twig",
                "context" => [
                    "contact_first_name"    => $contact->getFirstName(),
                    "contact_last_name"     => $contact->getLastName(),
                    "contact_email"         => $contact->getEmail(),
                    "contact_phone"         => $contact->getPhone(),
                    "contact_message"       => $contact->getMessage(),
                ]
            ]);

            // Générer un message flash de succès
            $this->addFlash("success", "Le message a bien été envoyé. Nous vous répondons rapidement.");

            // Effectuer une redirection vers la page d'accueil afin de consulter le nouveau contact ajouté dans la liste
            // Puis arrêter l'exécution du script
            return $this->redirectToRoute('visitor_contact_index');
        }

        // 3- Passons la partie visible du formulaire à la page (vue) pour affichage
        return $this->render("pages/visitor/contact/index.html.twig", [
            "form" => $form->createView(),
            "setting" => $settingRepository->find(4)
        ]);
    }

}
