<?php

namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

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
     * @param null $psCategory
     * @param string $psLocale
     * @return QueryBuilder
     */
    public function searchByCategory($psCategory = null, $psLocale = 'en_US')
    {
        // ==== Initialisation ====
        $lsLocale = strtolower(substr($psLocale, -2, 2));
        $loNow = new \DateTime();
        $loNow->setTime(23, 59, 59);

        $loQuery = $this->createQueryBuilder('o')
                        ->select('o')
                        ->where('o.active = 1')
                        ->andWhere('o.country = :locale')
                        ->setParameter('locale', $lsLocale)
                        ->andWhere('o.dateStart <= :now')
                        ->setParameter('now', $loNow)
                        ->orderBy('o.dateCreate', 'DESC');

        // ==== Pas de filtre pour le tag all ====
        if (!empty($psCategory)) {
            $loQuery->andWhere('o.category like :category')
                    ->setParameter('category', '%'.$psCategory.'%');
        }

        return $loQuery->getQuery()->getResult();
    } // searchByCategory

    /**
     * Lecture des 6 dernières offres
     *
     * @param $piNumber
     * @param string $psLocale
     * @return array
     */
    public function getLast($piNumber, $psLocale = 'en_US')
    {
        // ==== Initialisation ====
        $lsLocale = strtolower(substr($psLocale, -2, 2));
        $loNow = new \DateTime();
        $loNow->setTime(23, 59, 59);

        $loQuery = $this->createQueryBuilder('o')
                        ->select('o, partial b.{id, name}')
                        ->join('o.brand', 'b')
                        ->where('o.active = 1')
                        ->andWhere('o.country = :locale')
                        ->setParameter('locale', $lsLocale)
                        ->andWhere('o.dateStart <= :now')
                        ->setParameter('now', $loNow)
                        ->orderBy('o.dateCreate', 'DESC')
                        ->setMaxResults($piNumber);

        return $loQuery->getQuery()->getResult();
    } // getLast

    /**
     * Lecture des ids des offres recommandées pour un utilisateur
     *
     * @param $poUser
     * @return array
     */
    public function getRecommendedIdsByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('o')
                        ->select('o.id, r.type')
                        ->leftjoin('o.recommendations', 'r')
                        ->where('r.user = :user')
                        ->setParameter('user', $poUser);

        $laRecommendedOffers = array();
        foreach ($loQuery->getQuery()->getScalarResult() as $laRecommendation) {
            $laRecommendedOffers[$laRecommendation['id']][] = $laRecommendation['type'];
        }

        return $laRecommendedOffers;
    } // getRecommendedIdsByUser
}
