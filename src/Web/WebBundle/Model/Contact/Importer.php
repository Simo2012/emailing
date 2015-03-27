<?php
namespace Web\WebBundle\Model\Contact;

use IteratorAggregate;
use ArrayIterator;
use Doctrine\Common\Persistence\ObjectManager;
use Natexo\ToolBundle\Model\Filter\ApiDecryptFilter;
use Web\WebBundle\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Définition d'une classe pour l'import de contact
 *
 * <pre>
 * Victor 22/02/2015 Création
 * </pre>
 * @author Victor
 * @version 1.0
 * @package Rubizz
 */
abstract class Importer implements \IteratorAggregate
{
    private $contacts = array();

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ApiDecryptFilter
     */
    private $decrypter;

    /**
     * Constructeur
     *
     * @param ObjectManager $poManager
     */
    public function __construct(ObjectManager $poManager, ApiDecryptFilter $poDecrypter, RequestStack $poStack) {
        // ==== Initialisation ====
        $this->manager = $poManager;
        $this->decrypter = $poDecrypter;
        $this->request = $poStack->getCurrentRequest();
    } // __construct

    /**
     * retourne l'iterateur
     *
     * @return ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->contacts);
    }

    /**
     * Retourne la requête courante
     *
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    public function getRequest() {
        // ==== Initialisation ====
        return $this->request;
    } // getRequest

    /**
     * Renvoi le contenu du cookie
     *
     * @return array
     */
    public function getCookieContent() {
        // ==== Initialisation ====
        if ($this->request->cookies->has('RBZ')) {
            $lsCookie = $this->request->cookies->get('RBZ');
            $laDecrypt = $this->decrypter->filter($lsCookie);
            return $laDecrypt;
        } else {
            return array();
        }
    } // getCookieContent

    /**
     * Enregistre les contacts
     *
     * @param $poUser
     */
    public function saveContacts() {
        $laCookie = $this->getCookieContent();
        if (!empty($laCookie)) {
            foreach ($this->getIterator() as $laContact) {
                // ---- Insertion via PDO (ignore les doublons) ----
                $lsSql  = "INSERT IGNORE INTO contact SET date_create = NOW(), date_update = NOW(), user_id = :user_id,";
                $lsSql .= " firstname = :firstname, lastname = :lastname, email = :email, subscriber = :subscriber,";
                $lsSql .= " direct_unsubscribe = :direct_unsubscribe";
                $loStmt = $this->manager->getConnection()->prepare($lsSql);
                $laParam['user_id'] = $laCookie['userId'];
                $laParam['email'] = $laContact['email'];
                $laParam['firstname'] = (!empty($laContact['firstname'])) ? $laContact['firstname'] : '';
                $laParam['lastname'] = (!empty($laContact['lastname'])) ? $laContact['lastname'] : '';
                $laParam['subscriber'] = 1;
                $laParam['direct_unsubscribe'] = 0;
                $loStmt->execute($laParam);
            }
        }

    } // saveContacts

    public function addContact($paContact) {
        // ==== Initialisation ====
        $laContact = array(
            'lastname'  => null,
            'firstname' => null,
            'email'     => null,
        );
        $laContact = array_merge($laContact, $paContact);
        $this->contacts[] = $laContact;
    } // addContact
}
