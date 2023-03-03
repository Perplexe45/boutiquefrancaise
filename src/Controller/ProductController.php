<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SearchType;
use App\Classe\Search;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) //injection de dépendance
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response  //Pour faire des requête avec le form
    {
        //Recherche de tous les produits grace à ProductRepository.php (méthode "findAll)
        //$products = $this->entityManager->getRepository(product::class)->findAll();

        $search = new Search(); //Instance de la class créé "Search" manuellement sans aide de symfony (Search.php ) 
        $form = $this->createForm(SearchType::class, $search); //Construction du formulaire amené par SearchType  

        $form->handleRequest($request); //Ecoute si le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()) { //Si le  formulaire a été remplie
            //$search = $form->getData(); --> Ligne redondante
            $products = $this->entityManager->getRepository(product::class)->findWithSearch($search);
        }else{
            $products = $this->entityManager->getRepository(product::class)->findAll();
        }


        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        //Recherche de tous les produits grace à ProductRepository.php (méthode "findAll)
        $product = $this->entityManager->getRepository(product::class)->findOneBySlug($slug);

        //($slug) --> vient de l'entité "product", il y a une propriété "slug"
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        //dd($product);
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
