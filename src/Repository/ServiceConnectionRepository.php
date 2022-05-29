<?php

namespace App\Repository;

use App\Entity\ServiceConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceConnection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceConnection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceConnection[]    findAll()
 * @method ServiceConnection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceConnectionRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceConnection::class);
    }

    /**
     * @return ServiceConnection
     */
    public function create(): ServiceConnection
    {
        return new ServiceConnection();
    }

    /**
     * @param ServiceConnection[] $serverOptions
     */
    public function saveAll(array $serverOptions): void
    {
        foreach ($serverOptions as $serverOption) {
            $this->getEntityManager()->persist($serverOption);
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
     * @param ServiceConnection $serverOption
     */
    public function save(ServiceConnection $serverOption): void
    {
        $this->getEntityManager()->persist($serverOption);
        $this->getEntityManager()->flush();
    }
}