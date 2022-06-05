<?php

namespace App\Repository;

use App\Entity\ServiceJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

/**
 * @method ServiceJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceJob[]    findAll()
 * @method ServiceJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceJobRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceJob::class);
    }

    /**
     * @return ServiceJob
     */
    public function create(): ServiceJob
    {
        return new ServiceJob();
    }

    /**
     * @param ServiceJob[] $serverOptions
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
     * @param ServiceJob $serviceJob
     */
    public function save(ServiceJob $serviceJob): void
    {
        $this->getEntityManager()->persist($serviceJob);
        $this->getEntityManager()->flush();
    }
}
