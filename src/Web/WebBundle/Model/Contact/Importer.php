<?php
namespace Web\WebBundle\Model\Contact;

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

    public function getIterator() {
        return new ArrayIterator($this->contacts);
    }

    public function saveContacts() {
        foreach ($this->getIterator() as $laItem) {
            var_dump($laItem);
        }
    } // saveContacts
}
