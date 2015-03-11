<?php

namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CommissionsRepository
 *
 * <pre>
 * Mohammed 10/03/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package RUBIZZ
 */
class CommissionRepository extends EntityRepository
{

    /**
     * Recherche les commissions pour un user
     *
     * @param array $paData
     * @param array $paRestriction
     * @return query la requete
     */
    public function getUserCommissions($poUser,$poDate)
    {
        $loDate = explode('.',$poDate);
        $loQuery = $this->createQueryBuilder('c')
                ->select('c, u, r, o')
                ->join('c.recommendation', 'r')
                ->join('r.offer', 'o')
                ->join('r.user', 'u')
                ->andWhere('u = :user')
                ->andWhere('month(c.dateCreate) = :month')
                ->andWhere('year(c.dateCreate)= :year')
                ->setParameter('user', $poUser)
                ->setParameter('month', $loDate[0])
                ->setParameter('year', $loDate[1])
                ->orderBy('c.dateCreate', 'DESC');
        return $loQuery->getQuery()->getResult();
    }//getUserTransactions
    
}
