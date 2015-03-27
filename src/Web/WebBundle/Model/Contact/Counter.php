<?php

namespace Web\WebBundle\Model\Contact;

use Doctrine\Common\Persistence\ObjectManager;
use Web\WebBundle\Entity\User;

/**
 * Mets a jour le nombre de contacts d'un utilisateur
 *
 * <pre>
 * Victor 27/03/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package 
 */
class Counter
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $poManager)
    {
        // ==== Initialisation ====
        $this->manager = $poManager;
    } // __contruct

    /**
     * Met a jour le nombre de contact de l'utilisateur
     *
     * @param User $poUser
     */
    public function update(User $poUser)
    {
        // ==== Initialisation ====
        $laCounts = $this->manager->getRepository('WebWebBundle:Contact')->countByUser($poUser);
        $poUser->setNbContacts($laCounts[0]['total']);
        $this->manager->flush();
    } // update
}
