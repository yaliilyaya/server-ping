<?php

namespace App\Repository;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param ServiceCommand $command
     * @return ArrayCollection
     */
    public function findAllByCommand(ServiceCommand $command): ArrayCollection
    {
        $list = $this->findBy([
            'isActive' => true,
            'command' => $command
        ]);

        return new ArrayCollection($list);
    }

    /**
     * @param Collection $jobs
     * @return void
     */
    public function removeAll(Collection $jobs)
    {
        foreach ($jobs as $job) {
            $this->getEntityManager()->remove($job);
        }
        $this->getEntityManager()->flush();
    }
}
