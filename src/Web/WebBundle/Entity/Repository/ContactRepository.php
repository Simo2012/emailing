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
    public function getAll()
    {
        $loQuery = $this->createQueryBuilder('c')
                        ->select('c');

        return $loQuery;
    }
}