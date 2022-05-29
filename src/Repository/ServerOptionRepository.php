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
        parent::__construct($registry, ServerOption::class);
    }

    /**
     * @return ServerOption
     */
    public function create(): ServerOption
    {
        return new ServerOption();
    }

    /**
     * @param ServerOption[] $serverOptions
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
     * @param ServerOption $serverOption
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(ServerOption $serverOption): void
    {
        $this->getEntityManager()->persist($serverOption);
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
