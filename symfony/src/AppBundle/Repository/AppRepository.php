<?php

namespace AppBundle\Repository;

/**
 * AppRepository
 *
 */
class AppRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get random entity
     *
     * @return entity
     */
    public function getRandomEntity()
    {
        return $this->createQueryBuilder('q')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get random entities
     *
     * @param int $count Entities count, default is 10
     *
     * @return array
     */
    public function getRandomEntities($count = 10)
    {
        return $this->createQueryBuilder('q')
            ->addSelect('RAND() as HIDDEN rand')
            ->addOrderBy('rand')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }
}
