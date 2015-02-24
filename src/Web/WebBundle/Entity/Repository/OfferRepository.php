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

    /** chercher par categorie
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function searchByCategory($psCategory = null)
    {
        $loQuery = $this->createQueryBuilder('o')
                ->select('o')
                ->orderBy('o.dateCreate', 'DESC');
        //on filtre la categorie si demandé
        if (!empty($psCategory)) {
            $loQuery->where('o.category in (:category)')
                    ->setParameter('category', $psCategory);
        }


        return $loQuery->getQuery()->getResult();
    }//searchByCategory
}
