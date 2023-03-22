<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresses', name: 'account_address')] // route dans "compte" car défini dans "security.yaml --> ROLE_USER 
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }
    ////////////////////////
    //Ajouter une adresse
    ///////////////////////
    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')] // route dans "compte" car défini dans "security.yaml --> ROLE_USER 
    public function add(Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request); //handleRequest : manipule la requête entrante pour le formulaire
        if ($form->isSubmitted() && $form->isValid()) { //Si les champs du "form" sont bien remplis
            $address->setUser($this->getUser()); //On récupère l'identif  
            $this->entityManager->persist($address);  //Préparation des données de l'objet address pour la base de donnée
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
         
            
        }

        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    /////////////////////////
    ////Modifier une adresse
    ///////////////////////
    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')] // route dans "compte" car défini dans "security.yaml --> ROLE_USER 
    public function edit(Request $request, $id): Response //Recup de l'id avec l'URL de la route
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id); //$id vient de l'URL

        if (!$address || $address->getUser() != $this->getUser()) { //si l'adresse de celui qui est bien connecté (grace à '$id' est bien celui qui correspond à la BD
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request); //handleRequest : manipule la requête entrante pour le formulaire
        if ($form->isSubmitted() && $form->isValid()) { //Si les champs du "form" sont bien remplis
            $this->entityManager->flush(); //MAJ de l'adresse avec flush
            return $this->redirectToRoute('account_address');
         
            
        }

        return $this->render('account/address_form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    //////////////////////////////
    /////Suppression d'une adresse
    //////////////////////////////
    #[Route('/compte/supprimer-une-adresse/{id}', name: 'account_address_delete')] // route dans "compte" car défini dans "security.yaml --> ROLE_USER 
    public function delete($id): Response //Recup de l'id avec l'URL de la route
    {
        
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id); //$id vient de l'URL

        if ($address && $address->getUser() == $this->getUser()) { //si l'adresse de celui qui est bien connecté est bien celui qui correspond à la BD
            $this->entityManager->remove($address); //Suppression de l'adresse
            $this->entityManager->flush(); //MAJ de l'adresse avec flush : On valide


        return $this->redirectToRoute('account_address');
    }
}
}
