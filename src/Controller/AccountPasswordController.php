<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class AccountPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response //Objet Request qui se charge dans $request
    {
        $notification = null;

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request); //handleRequest : manipule la requête entrante pour le formulaire

        if ($form->isSubmitted() && $form->isValid()) { //Si les champs du "form" sont bien remplis
            $old_pwd = $form->get('old_password')->getData(); //Récupération de la data de cet input du form "ChangePasswordType" 
            if ($encoder->isPasswordValid($user, $old_pwd)) {   //Récupération du nouveau password
                $new_pwd = $form->get('new_password')->getData(); //Récupération de la nouvelle data de l'input
                $password = $encoder->hashPassword($user, $new_pwd); //encodage du nouveau password
                //dd($password); --> ok, ça fonctionne

                $user->setPassword($password); //Erreur symfony, mais la modif du password est ok !... 
                $this->entityManager->flush(); //Modif dans l'entité.

                // Ajout d'un message pour indiquer que le mot de passe a été modifié avec succès
                $notification = ('Votre mot de passe a été modifié avec succès.');
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('account/password.html.twig', [ //Dans le dossier templates
            'form' => $form->createView(), //Création de la vue du formulaire
            'notification' => $notification
        ]);
    }
}
