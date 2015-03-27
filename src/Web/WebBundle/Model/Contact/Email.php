<?php

namespace Web\WebBundle\Model\Contact;

use Doctrine\Common\Persistence\ObjectManager;
use Web\WebBundle\Entity\User;

/**
 * Model d'ajout de contact à partir d'emails
 *
 * <pre>
 * victor 24/03/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package 
 */
class Email
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Counter
     */
    private $counter;

    /**
     * Constructor
     *
     * @param ObjectManager $poManager
     */
    public function __construct(ObjectManager $poManager, Counter $poCounter)
    {
        // ==== Initialisation ====
        $this->manager = $poManager;
        $this->counter = $poCounter;
    } // __construct

    /**
     *
     * @param array $paEmails
     * @param User $poUser
     */
    public function addContactsFromEmails(array $paContacts, User $poUser)
    {
        // ==== Initialisation ====
        if (empty($paContacts)) {
            return;
        }
        foreach ($paContacts as $laContact) {
            // ---- Insertion via PDO (gain de perf (insert or duplicate key) ----
            $lsSql  = "INSERT IGNORE INTO contact SET date_create = NOW(), date_update = NOW(), user_id = :user_id,";
            $lsSql .= " firstname = :firstname, lastname = :lastname, email = :email, subscriber = :subscriber,";
            $lsSql .= " direct_unsubscribe = :direct_unsubscribe";
            $loStmt = $this->manager->getConnection()->prepare($lsSql);
            $laParam['user_id'] = $poUser->getId();
            $laParam['email'] = $laContact['email'];
            $laParam['firstname'] = (!empty($laContact['firstname'])) ? $laContact['firstname'] : '';
            $laParam['lastname'] = (!empty($laContact['lastname'])) ? $laContact['lastname'] : '';
            $laParam['subscriber'] = 1;
            $laParam['direct_unsubscribe'] = 0;
            $loStmt->execute($laParam);
        }

        // ==== Mise à jour du nombre de contacts ====
        $this->counter->update($poUser);
    } // addContactsFromEmails
}
