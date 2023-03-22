<?php

namespace App\Classe;
//use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{
    private  $session;
    private $entityManager;
   

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id){

        //cherche la session 'cart' si pas de session : renvoie un tableau nul
        $cart = $this->session->get('cart',[]);

        if (!empty($cart[$id])) { //produit deja exitant du même identif inséré dans le panier.
            $cart[$id]++; //incrémentation : Ajout d'une quantité
        }else{
            $cart[$id] = 1; //Ce produit n'est pas encore dans le panier
        }

        $this->session->set('cart', $cart) ;   
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete ($id)
    {
        $cart = $this->session->get('cart',[]);
        unset ($cart[$id]); //destruction de la variable $id
        return $this->session->set('cart',$cart); //nouveau panier dans la session 'cart'
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart',[]);
        if ($cart[$id]>1) {
            //retirer une quantité (-1)
            $cart[$id]--;
            
        }else{
            //Supprimer le produit
            unset ($cart[$id]); //destruction de la variable $id car plus de produit
        }
        return $this->session->set('cart',$cart); //nouveau panier dans la session 'cart'
        

    }

    public function getFull() //va aussi gérer un id dans l'url qui n'existe pas dans la BD : Sécurité  
    {
        $cartComplete = []; //le panier complet dans un tableau
        
        if ($this->get()){ //pour éviter d'avoir une erreur si le panier est vide
            foreach ($this->get() as $id => $quantity) { //-> get : fonction visible dans cette classe
                $product_object = $this->entityManager->getRepository(Product::class)->findOneBy(['id' => $id]);
                if(!$product_object){ //le produit n'existe pas : voici la sécurité
                    $this->delete($id); //$this : présent dans cette classe et suppression de $id : dans la session cart
                    continue; //on sort du foreach : le code suivant n'est pas exécuté et il n'y aura pas de return.
                }
                    $cartComplete[] = [
                    'product' => $product_object,
                    'quantity'=>$quantity
                ];
              }
            };
            return $cartComplete;
        }
   }
    
   



















?>
