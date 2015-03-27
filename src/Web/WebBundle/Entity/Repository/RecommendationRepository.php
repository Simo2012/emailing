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
class RecommendationRepository extends EntityRepository
{
    /**
     * @todo Supprimer cette fonction dès que l'on aura des vraies donnéesà afficher dans la page de recommandations
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAll()
    {
        $loQuery = $this->createQueryBuilder('r')
                        ->select('r, o')
                        ->leftjoin('r.offer', 'o');

        return $loQuery;
    } //getAll
    
    /**
     * Retourne la liste des recommandations faites par un utilisateur pour ses contacts
     *
     * @param type $poUser
     * @return type
     */
    public function getAllByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('r')
                        ->select('r, o, c')
                        ->join('r.offer', 'o')
                        ->leftjoin('r.contact', 'c')
                        ->where('r.user = :user')
                        ->setParameter('user', $poUser)
                        ->groupBy('c.id');

        return $loQuery;
    }//getAllByUser

    /**
     * Lecture des recommandations par utilisateur
     *
     * @param $poUser
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('r')
                        ->select(
                            'r, o, c, c.amount as commission, count(c.id) as volume, c.amount * count(c.id) as total'
                        )
                        ->join('r.commissions', 'c')
                        ->join('r.offer', 'o')
                        ->where('r.user = :user')
                        ->setParameter('user', $poUser)
                        ->groupBy('r.id');

        return $loQuery;
    } // getByUser

    /**
     * Méthode findBy customisée pour charger les entités user, offer et contact
     * et éviter le lazy loading
     *
     * @param $paCriterias
     * @return array
     */
    public function getBy($paCriterias)
    {
        $loQuery = $this->createQueryBuilder('r')
                        ->select('r, o, u, c')
                        ->join('r.offer', 'o')
                        ->join('r.user', 'u')
                        ->join('u.contacts', 'c')
                        ->where('c.subscriber = 1 and c.directUnsubscribe = 0');

        foreach ($paCriterias as $lsCriteria => $lsValue) {
            $loQuery->andWhere("r.{$lsCriteria} = :{$lsCriteria}")
                    ->setParameter($lsCriteria, $lsValue);
        }

        return $loQuery->getQuery()->getResult();
    } // getBy
}