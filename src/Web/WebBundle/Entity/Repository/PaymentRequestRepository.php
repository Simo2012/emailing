<?php
namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PaymentRequest repository
 *
 * <pre>
 * Elias 21/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Rubizz
 */
class PaymentRequestRepository extends EntityRepository
{
    /**
     * calculer les gains par mois
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function earningByMonth($poUser)
    {
        $loQuery = $this->createQueryBuilder('pr')
                        ->select(
                            'sum(pr.amount) as amount',
                            'MONTH(pr.dateCreate) as month'
                        )
                        ->where('pr.user = :user')
                        ->setParameter('user', $poUser)
                        ->groupBy('year(pr.date_create)')
                        ->groupBy('month')
                        ->orderBy('pr.dateCreate', 'ASC');

        return $loQuery->getQuery()->getResult();
    }

     /**
      * Retourner la liste de transaction
      * @return \Doctrine\ORM\QueryBuilder
      */
    public function getAllByUser($poUser,$poDate)
    {
        $loDate = explode('.',$poDate);
        $loQuery = $this->createQueryBuilder('pr')
                        ->select('pr')
                        ->where('pr.user = :user')
                        ->andWhere('month(pr.dateCreate) = :month')
                        ->andWhere('year(pr.dateCreate)= :year')
                        ->setParameter('user', $poUser)
                        ->setParameter('month', $loDate[0])
                        ->setParameter('year', $loDate[1])
                        ->setParameter('user', $poUser);

        return $loQuery->getQuery()->getResult();
    }
}
