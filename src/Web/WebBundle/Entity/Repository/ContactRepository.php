<?php
namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Contact repository
 *
 * <pre>
 * Julien 17/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
class ContactRepository extends EntityRepository
{
    /**
     * Lecture des contacts d'un utilisateur
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getByUser($poUser, $laFilters)
    {
        $loQuery = $this->createQueryBuilder('c')
                        ->select('c', 'sum(cm.amount) as commissions')
                        ->leftjoin('c.commissions', 'cm')
                        ->where('c.user = :user')
                        ->setParameter('user', $poUser)
                        ->groupBy('c.id');
        // ---- Recherche par nom ----
        if (!empty($laFilters['name'])) {
            $loQuery->andWhere('c.firstname like :name or c.lastname like :name')
                    ->setParameter('name', '%'.$laFilters['name'].'%');
        }

        return $loQuery;
    } // getByUser

    /**
     * Comptage des contacts d'un utilisateur
     *
     * @return array
     */
    public function countByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('c')
                        ->select(
                            'sum(if(c.subscriber = 1 and c.directUnsubscribe = 0, 1, 0)) as subscribed',
                            'sum(if(c.subscriber = 1 and c.directUnsubscribe = 0, 0, 1)) as unsubscribed'
                        )
                        ->where('c.user = :user')
                        ->setParameter('user', $poUser);

        return $loQuery->getQuery()->getScalarResult();
    } // countByUser
}