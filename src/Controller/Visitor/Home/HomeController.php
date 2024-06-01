<?php

namespace App\Controller\Visitor\Home;


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

class HomeController extends AbstractController
{
    #[Route('/', name: 'visitor_home_index', methods: ['GET', 'SET', 'POST'])]
    public function index(SettingRepository $settingRepository): Response
    {
        // 1- Créons l'instance du contact qui doit être ajouté en bdd
        $contact = new Contact();

        // 2- Créons le formulaire en se basant sur son type
        $form = $this->createForm(ContactFormType::class, $contact, [
            'action' => $this->generateUrl('visitor_contact_index')
        ]);
        
        return $this->render('pages/visitor/home/index.html.twig', [
            "form" => $form->createView(),
            "setting" => $settingRepository->find(4)
        ]);
    }
}
