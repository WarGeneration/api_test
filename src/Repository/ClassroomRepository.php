<?php

namespace App\Repository;

use App\Entity\Classroom;
use Artprima\QueryFilterBundle\Query\ConditionManager;
use Artprima\QueryFilterBundle\Query\ProxyQueryBuilder;
use Artprima\QueryFilterBundle\QueryFilter\QueryFilterArgs;
use Artprima\QueryFilterBundle\QueryFilter\QueryResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Classroom|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classroom|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classroom[]    findAll()
 * @method Classroom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClassroomRepository extends ServiceEntityRepository
{
    /**
     * @var ConditionManager
     */
    private $pqbManager;

    public function __construct(ManagerRegistry $registry, ConditionManager $manager)
    {
        parent::__construct($registry, Classroom::class);
        $this->pqbManager = $manager;
    }

    public function findByOrderBy(QueryFilterArgs $args): QueryResult
    {
        $qb = $this->createQueryBuilder('o')
            ->setFirstResult($args->getOffset())
            ->setMaxResults($args->getLimit());

        $proxyQb = new ProxyQueryBuilder($qb, $this->pqbManager);
        $qb = $proxyQb->getSortedAndFilteredQueryBuilder($args->getSearchBy(), $args->getSortBy());
        $query = $qb->getQuery();
        $paginator = new Paginator($query);

        return new QueryResult($paginator->getIterator()->getArrayCopy(), count($paginator));
    }
}
