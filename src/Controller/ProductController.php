<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) //injection de dépendance
    {  
        $this->entityManager =$entityManager;
    }

    #[Route('/nos-produits', name: 'products')]
    public function index(): Response
    {
        //Recherche de tous les produits grace à ProductRepository.php (méthode "findAll)
        $products = $this->entityManager->getRepository(product::class)->findAll();

        return $this->render('product/index.html.twig',[
            'products'=>$products
        ] );
    }

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug): Response
    {
        // dd($slug);
        //Recherche de tous les produits grace à ProductRepository.php (méthode "findAll)
         $product = $this->entityManager->getRepository(product::class)->findOneBySlug($slug);
         
        //($slug) --> vient de l'entité "product", il y a une propriété "slug"
        if(!$product){
            return $this->redirectToRoute('products'); 
        }

        //dd($product);
        return $this->render('product/show.html.twig',[
            'product'=>$product
        ] );
    }
}
