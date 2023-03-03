<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
      * Requête qui me permet de récupérer les produits en fonction de la recherche de l'utilisateur
      * @return Product []
      */

    public function findWithSearch (Search $search)  //Injection de dépendance de la class Search(.php) dans la var $search
    {
        /////////////Recherche avec les catégories de produit//////////////////

        $query = $this //Préparation de la requête- Les alias 'p'et'c' sont directement créés par Doctrine
        ->createQueryBuilder('p') //Construction de la requête et  'p': Alias de 'Product'
        ->select('c','p') //Alias de category et product :  (Selection des 2 tables)
        ->join('p.category','c');//Jointure entre les catégories du produit et la table "category'

        if (!empty($search->categories)) { //@var categories de la classe "Search.php""
            $query = $query
                ->andWhere('c.id IN (:categories)') //id des catégories dans la liste (:categories) envoyé en paramètre de l'objet "Search"
                ->setParameter('categories',$search->categories); //Valeurs de 'catégories' --> dans $Search->Categories 
        }

        //////////////Recherche vac le nom du produit///////////////////////
       
         if(!empty($search->string)) { //@var string de la classe "Search.php"" et  est-ce qu'une demande de recherche textuelle a été demandée
            $query = $query
                ->andWhere('p.name LIKE :string') //nom du produit existant dans l'objet 'Research' ?
                ->setParameter('string',"%{$search->string}%"); //Valeurs de 'produit' --> dans $Search->string
        }

        return $query->getQuery()->getResult(); //Retourne la construction de la requête, ainsi que du résultat.
    }



    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
