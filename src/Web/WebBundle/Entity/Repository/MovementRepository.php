<?php
namespace Web\WebBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Movement repository
 *
 * <pre>
 * Mohammed 10/03/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Rubizz
 */
class MovementRepository extends EntityRepository
{
    /**
     * @todo Supprimer cette fonction dès que l'on aura des vraies donnéesà afficher dans la page de movements
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllByUser($poUser)
    {
        $loQuery = $this->createQueryBuilder('m')
                        ->select('m')
                        ->where('m.user = :user')
                        ->setParameter('user', $poUser)
                        ->orderBy('m.dateMovement', 'DESC');

        return $loQuery;
    }
    
}