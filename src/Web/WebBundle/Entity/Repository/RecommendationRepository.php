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
     * Il retourne la liste des recommandation faite par ce utilisateur pour ces contacts 
     * @param type $poUser
     * @return type
     */
    public function getAllByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('r')
                        ->select('r, o, c')
                        ->leftjoin('r.offer', 'o')
                        ->leftjoin('r.contact', 'c')
                        ->where('r.user = :user')
                        ->setParameter('user', $poUser)
                        ->groupBy('c.id');

        return $loQuery;
    }//getAllByUser
}