<?php

namespace Web\WebBundle\Model\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * Modèle UserPasswordEncoder : gère l'encodage et le décodage des mots de passe des utilisateurs
 *
 * <pre>
 * Julien 10/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package publisher
 */
class UserPasswordEncoder implements PasswordEncoderInterface
{
    /**
     * Modèle d'encryptage d'une chaine de caractère
     * @var \Natexo\ToolBundle\Model\Filter\ApiEncryptFilter
     */
    private $apiEncryptFilter;

    /**
     * Contructeur
     * @param $apiEncryptFilter
     */
    public function __construct($apiEncryptFilter)
    {
        $this->apiEncryptFilter = $apiEncryptFilter;
    }

    /**
     * Encode le password
     * @param string $raw
     * @param string $salt
     * @return string
     */
    public function encodePassword($raw, $salt)
    {
        // ==== Vérifie la longueur du mot de passe ====
        if (!$this->checkPasswordLength($raw)) {
            return false;
        }

        // ==== Encodage ====
        return $this->apiEncryptFilter->filter(array($raw));
    }

    /**
     * Vérifie la validité du password
     * @param string $encoded
     * @param string $raw
     * @param string $salt
     * @return bool|void
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        // ==== Vérifie la longueur du mot de passe ====
        if (!$this->checkPasswordLength($raw)) {
            return false;
        }

        // ==== Vérification ====
        if ($encoded != $raw) {
            return true;
        }

        return false;
    }

    /**
     * Test la longueur du mot de passe
     * @param $raw
     * @return bool
     */
    public function checkPasswordLength($raw)
    {
        if (strlen($raw) <= 12) {
            return true;
        }

        return false;
    }
}