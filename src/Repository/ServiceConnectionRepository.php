<?php

namespace App\Repository;

use App\Entity\ServiceConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

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
        $serviceConnection = new ServiceConnection();
        $serviceConnection->setIp('127.0.0.1');
        $serviceConnection->setIsActive(false);
        return $serviceConnection;
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
