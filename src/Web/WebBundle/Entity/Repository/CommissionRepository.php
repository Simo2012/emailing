<?php

namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Web\WebBundle\Entity\Offer;

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

    /**
     * Compte le nombre de commissions pour une offre
     *
     * @param Offer $poOffer L'offre à considérer
     * @return integer Le nombre
     */
    public function countOffer(Offer $poOffer)
    {
        $loQuery = $this->createQueryBuilder('c')
                        ->select('count(c.id) as nb')
                        ->join('c.recommendation', 'r')
                        ->where('r.offer = :offer')
                        ->setParameter('offer', $poOffer);
        $laResult = $loQuery->getQuery()->getScalarResult();
        return $laResult[0]['nb'];
    } // countOffer
}
