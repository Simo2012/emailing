<?php

namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OfferRepository repository
 *
 * <pre>
 * Elias 24/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Rubizz
 */
class OfferRepository extends EntityRepository
{

    /**
     * Recherche par categorie
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function searchByCategory($psCategory = null)
    {

        $loQuery = $this->createQueryBuilder('o')
                        ->select('o')
                        ->orderBy('o.dateCreate', 'DESC');
        // ==== Pas de filtre pour le tag all ====
        if (!empty($psCategory)) {
            $loQuery->where('o.category in (:category)')
                    ->setParameter('category', $psCategory);
        }

        return $loQuery->getQuery()->getResult();
    } // searchByCategory

    /**
     * Lecture des 6 dernières offres
     *
     * @return array
     */
    public function getLast($piNumber)
    {
        $loQuery = $this->createQueryBuilder('o')
                        ->select('o, partial b.{id, name}')
                        ->join('o.brand', 'b')
                        ->orderBy('o.dateCreate', 'DESC')
                        ->setMaxResults($piNumber);

        return $loQuery->getQuery()->getResult();
    } // getLastSix
}
