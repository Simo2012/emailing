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
    public function getUserCommissions($poUser)
    {
        $loQuery = $this->createQueryBuilder('c')
                ->select('c, u, r, o')
                ->join('c.recommendation', 'r')
                ->join('r.offer', 'o')
                ->join('r.user', 'u')
                ->where('u = :user')
                ->setParameter('user', $poUser)
                ->orderBy('c.dateCreate', 'DESC');
        return $loQuery->getQuery()->getResult();
    }//getUserTransactions
    
}
