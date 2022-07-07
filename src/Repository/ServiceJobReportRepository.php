<?php

namespace App\Repository;

use App\Collection\ServiceJobReportCollection;
use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

/**
 * @method ServiceJobReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceJobReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceJobReport[]    findAll()
 * @method ServiceJobReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceJobReportRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceJobReport::class);
    }

    /**
     * @return ServiceJobReport
     */
    public function create(): ServiceJobReport
    {
        return new ServiceJobReport();
    }

    /**
     * @param ServiceJobReportCollection $serverOptions
     */
    public function saveAll(ServiceJobReportCollection $serverOptions): void
    {
        foreach ($serverOptions as $serverOption) {
            $this->getEntityManager()->persist($serverOption);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * @param ServiceJobReport $serviceJob
     */
    public function save(ServiceJobReport $serviceJob): void
    {
        $this->getEntityManager()->persist($serviceJob);
        $this->getEntityManager()->flush();
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
