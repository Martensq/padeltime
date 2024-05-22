<?php

namespace App\Controller\Visitor\Registration;


use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/inscription', name: 'visitor_registration_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Si l'utilisateur est déjà connecté
            // il n'a plus rien à faire sur le page de connexion
            // redirection vers la page d'accueil
            if ($this->getUser())
            {
                return $this->redirectToRoute('visitor_welcome_index');
            }
    
            // 1- Créer l'utilisateur à insérer en base de données
            $user = new User();
    
            // 2- Créer le formulaire d'inscription
            $form = $this->createForm(RegistrationFormType::class, $user);
    
            // 4- Associer au formulaire les données de la requête
            $form->handleRequest($request);
    
            // 5- Si le formulaire est soumis et valide
            if ($form->isSubmitted() && $form->isValid())
            {
                // 6- Encoder le mdp
                $passwordHashed = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
    
                // 7- Mettre à jour le mdp de l'user
                $user->setPassword($passwordHashed);
    
                // 8- Demander au manager des entités de préparer la requête d'insertion de l'utilisateur qui s'inscrit en base de données
                $entityManager->persist($user);
    
                // 9- Exécuter la requête
                $entityManager->flush();

            // 10- Envoyer l'email de vérification de compte à l'utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('padeltime@gmail.com', 'Padel Time'))
                    ->to($user->getEmail())
                    ->subject('Vérification de votre compte')
                    ->htmlTemplate('emails/confirmation_email.html.twig')
            );

            // 11- Rediriger l'utilisateur vers la page d'attente 
            return $this->redirectToRoute('visitor_registration_waiting_for_email_verification');
        }

        // 3- Passer le formulaire à la page pour affichage
        return $this->render('pages/visitor/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('inscription/email-en-attente-de-validation', name: 'visitor_registration_waiting_for_email_verification', methods: ['GET'])]
    public function waitingForEmailVerification(): Response
    {
       return  $this->render('pages/visitor/registration/waiting_for_email_verification.html.twig');
    }


    #[Route('/verification/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('visitor_authentication_login');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('visitor_authentication_login');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try
        {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        }
        catch (VerifyEmailExceptionInterface $exception)
        {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('visitor_authentication_login');
        }

        $this->addFlash('success', 'Votre compte a été validé, vous pouvez vous connecter.');

        return $this->redirectToRoute('visitor_authentication_login');
    }
}
