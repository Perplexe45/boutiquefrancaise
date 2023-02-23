<?php

namespace App\Controller;

use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
   
    #[Route('/inscription', name: 'app_register')]

    public function index(Request $request,UserPasswordHasherInterface $encodeur): Response //Injection de dépendance avec l'objet "Request"
    {																				//On embarque l'objet "Request" dans la public function "index"
          $user = new \App\Entity\User();				//L'instance de  l'objet Request sera chargé dans "$request'
          $form = $this->createForm(RegisterType::class,$user);

        $form->handleRequest($request); //Ecoute de la requête entrante avec "handleResquest" s'il y a un post 

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData(); //Injectionde toutes les données du formulaire de "RegisterType.php"
            $password = $user->getPassword(); //Récup du setter dans entité "User.php"

            //appeler la méthode hashPassword() sur l'objet $encodeur (qui est une instance de UserPasswordHasherInterface)
            // en lui passant l'objet User et le mot de passe en clair à hasher. La méthode hashPassword() retourne
            // le mot de passe hashé, qui est ensuite définit sur la propriété password de l'objet User.
            // Cela extrait le mot de passe en clair à partir de l'objet User et le hashe avec
            // l'objet $encodeur et de définir le mot de passe hashé sur la propriété password de l'objet User.
            $hashedPassword = $encodeur->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        
        return $this->render('register/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
