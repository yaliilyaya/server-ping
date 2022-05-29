<?php

namespace App\Repository;

use App\Entity\ServerOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServerOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerOption[]    findAll()
 * @method ServerOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    // /**
    //  * @return Item[] Returns an array of Item objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Item
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Item
     */
    public function create(): Item
    {
        return new Item();
    }

    /**
     * @param Item[] $items
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveAll(array $items): void
    {
        foreach ($items as $item) {
            $this->getEntityManager()->persist($item);
        }
        $this->getEntityManager()->flush();
    }

    public function clearItem(): void
    {
        $queryBuilder = $this->createQueryBuilder('item')
            ->delete();

        $queryBuilder->getQuery()->execute();
    }

    /**
     * @param Item $item
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Item $item): void
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $label
     * @return Item|null
     */
    public function findByLabel($label): ?Item
    {
        return $this->findOneBy([
            'label' => $label
        ]);
    }
}
