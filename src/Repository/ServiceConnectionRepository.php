<?php

namespace App\Repository;

use App\Entity\ServiceConnection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @param ServiceConnection $serviceConnection
     */
    public function save(ServiceConnection $serviceConnection): void
    {
        $this->getEntityManager()->persist($serviceConnection);
        $this->getEntityManager()->flush();
    }

    public function delete(ServiceConnection $serverOption)
    {
        $this->getEntityManager()->remove($serverOption);
        $this->getEntityManager()->flush();
    }

    /**
     * @return ArrayCollection
     */
    public function findActiveAll(): ArrayCollection
    {
        $list = $this->findBy([
            'isActive' => true
        ]);

        return new ArrayCollection($list);
    }
}
