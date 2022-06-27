<?php

namespace App\Repository;

use App\Entity\ServiceCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Collection;

/**
 * @method ServiceCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceCommand[]    findAll()
 * @method ServiceCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceCommandRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceCommand::class);
    }

    /**
     * @return ServiceCommand
     */
    public function create(): ServiceCommand
    {
        return new ServiceCommand();
    }

    /**
     * @param ServiceCommand[] $serverOptions
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
     * @param ServiceCommand $serverOption
     */
    public function save(ServiceCommand $serverOption): void
    {
        $this->getEntityManager()->persist($serverOption);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $commandType
     * @return ServiceCommand|null
     */
    public function findByType($commandType): ?ServiceCommand
    {
        return $this->findOneBy([
            'type' => $commandType
        ]);
    }

    /**
     * @param array $commandIds
     * @return ArrayCollection|ServiceCommand[]
     */
    public function findByIds(array $commandIds)
    {
        $commands = $this->findBy([
            'id' => $commandIds
        ]);

        return new ArrayCollection($commands);
    }

    /**
     * @param array $commandIds
     * @return ArrayCollection|ServiceCommand[]
     */
    public function findAllIgnoreIds(array $commandIds)
    {
        $commandsData = $this->findAll();
        $commands = new ArrayCollection($commandsData);

        return $commands->filter(function (ServiceCommand $command) use ($commandIds) {
            return !in_array($command->getId(), $commandIds);
        });
    }
}
