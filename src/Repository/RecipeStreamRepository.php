<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\RecipeStream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RecipeStream|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeStream|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeStream[]    findAll()
 * @method RecipeStream[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeStreamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecipeStream::class);
    }

    // /**
    //  * @return RecipeStream[] Returns an array of RecipeStream objects
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
    public function findOneBySomeField($value): ?RecipeStream
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
     * @return RecipeStream
     */
    public function create(): RecipeStream
    {
        return new RecipeStream();
    }

    /**
     * @param Collection $recipes
     */
    public function clearRecipeStream(Collection $recipes)
    {
        $recipeIds = array_map(function (Recipe $recipe) {
            return $recipe->getId();
        }, iterator_to_array($recipes));

        $queryBuilder = $this->createQueryBuilder('rs');
        $queryBuilder->delete()
            ->andWhere('rs.recipe in (:recipeIds)')
            ->setParameter('recipeIds', $recipeIds);

        if ($recipeIds) {
            $queryBuilder->getQuery()->execute();
        }
    }

    public function clearItem()
    {
        $queryBuilder = $this->createQueryBuilder('recipeStream')
            ->delete();

        $queryBuilder->getQuery()->execute();
    }
}
