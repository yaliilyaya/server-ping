<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Recipe
     */
    public function create(): Recipe
    {
        return new Recipe();
    }

    /**
     * @param Item $item
     */
    public function clearRecipe(Item $item)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->delete()
            ->andWhere('r.item = :itemId')
            ->setParameter('itemId', $item->getId());

        $queryBuilder->getQuery()->execute();
    }

    public function clearItem()
    {
        $queryBuilder = $this->createQueryBuilder('recipe')
            ->delete();

        $queryBuilder->getQuery()->execute();
    }
}
