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
    public function searchByCategory($psCategory = null, $psLocale = 'en_EN')
    {
        // ==== Formatage de la locale ====
        $lsLocale = strtolower(substr($psLocale, 0, 2));
        
        $loQuery = $this->createQueryBuilder('o')
                        ->select('o')
                        ->where('o.country = :locale')
                        ->setParameter('locale', $lsLocale)
                        ->andWhere('o.active = 1')
                        ->orderBy('o.dateCreate', 'DESC');

        // ==== Pas de filtre pour le tag all ====
        if (!empty($psCategory)) {
            $loQuery->andWhere('o.category in (:category)')
                    ->setParameter('category', $psCategory);
        }

        return $loQuery->getQuery()->getResult();
    } // searchByCategory

    /**
     * Lecture des 6 dernières offres
     *
     * @return array
     */
    public function getLast($piNumber, $psLocale = 'en_EN')
    {
        // ==== Formatage de la locale ====
        $lsLocale = strtolower(substr($psLocale, 0, 2));

        $loQuery = $this->createQueryBuilder('o')
                        ->select('o, partial b.{id, name}')
                        ->join('o.brand', 'b')
                        ->where('o.country = :locale')
                        ->setParameter('locale', $lsLocale)
                        ->andWhere('o.active = 1')
                        ->orderBy('o.dateCreate', 'DESC')
                        ->setMaxResults($piNumber);

        return $loQuery->getQuery()->getResult();
    } // getLast

    public function getRecommendedIdsByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('o')
            ->select('o.id')
            ->leftjoin('o.recommendations', 'r')
            ->where('r.user = :user')
            ->setParameter('user', $poUser);

        $laRecommendedOffers = array();
        foreach ($loQuery->getQuery()->getScalarResult() as $laRecommendation) {
            $laRecommendedOffers[] = $laRecommendation['id'];
        }

        return $laRecommendedOffers;
    } // getRecommendedIdsByUser
}
