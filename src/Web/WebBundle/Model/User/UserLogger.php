<?php
namespace Web\WebBundle\Model\User;

use Doctrine\ORM\EntityManager;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Web\WebBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

/**
 * Connexion des utilisateurs
 *
 * <pre>
 * Julien 11/02/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Rubizz
 */
class UserLogger
{
    /**
     * Doctrine entity manager
     * @var EntityManager
     */
    private $manager;
    /**
     * Gestionnaire d'encodeurs
     * @var EncoderFactory
     */
    private $factory;
    /**
     * Modèle de décodage des mots de passe encodés via l'encodeur
     * utilisant le modèle d'encodage ApiEncryptFilter
     * @var ApiDecryptFilter
     */
    private $apiDecryptFilter;
    /**
     * Requête courante
     * @var Request
     */
    private $request;
    /**
     * Traducteur de phrases
     * @var Translator
     */
    protected $translator;


    /**
     * Constructeur, injection des dépendances
     */
    public function __construct($poManager, $poFactory, $poApiDecryptFilter, $poRequestStack, $poTranslator)
    {
        $this->manager          = $poManager;
        $this->factory          = $poFactory;
        $this->apiDecryptFilter = $poApiDecryptFilter;
        $this->request          = $poRequestStack->getCurrentRequest();
        $this->translator       = $poTranslator;
    } // __construct

    /**
     * Récupère un utilisateur par son email et le connect
     *
     * @return User
     */
    public function logUser($poUser)
    {
        // ==== Lecture d'un utilisateur ====
        $lsEmail = $poUser->getEmail();
        $loUser = $this->manager->getRepository('WebWebBundle:User')->findOneByEmail($lsEmail);
        if (empty($loUser)) {
            throw new AuthenticationCredentialsNotFoundException(
                $this->translator->trans('web.web.security.email_not_found')
            );
        }
        $laPassword = $this->apiDecryptFilter->filter($loUser->getPassword());
        $lsPassword = $laPassword[0];
        if ($lsPassword !== $poUser->getPassword()) {
            throw new BadCredentialsException(
                $this->translator->trans('web.web.security.wrong_password')
            );
        }

        // ==== Mise en session de l'utilisateur ====
        $this->setUserInSession($loUser);

        return $loUser;
    } // getUser

    /**
     * Log l'utilisateur
     *
     * @param User $poUser Utilisateur
     */
    public function setUserInSession(User $poUser)
    {
        $loToken = new UsernamePasswordToken(
            $poUser,
            $poUser->getPassword(),
            'secured_users_area',
            $poUser->getRoles()
        );
        $loSession = $this->request->getSession();
        $loSession->set('_security_secured_users_area', serialize($loToken));
        $loSession->set('hasRegistered', true);

        // ==== Enregistrement de la date de login ====
        $poUser->setDateLogin(new \DateTime());
        $this->manager->flush();
    } // setUserInSession

    /**
     * Enregistre l'utilisateur et le log
     *
     * @param User $poUser Utilisateur
     * @return User
     * @throws \ErrorException
     */
    public function registerUser($poUser)
    {
        // ==== Création de l'utilisateur ====
        $lsEmail = $poUser->getEmail();
        $loUser = $this->manager->getRepository('WebWebBundle:User')->findOneByEmail($lsEmail);
        if (!empty($loUser)) {
            throw new \ErrorException(
                $this->translator->trans('web.web.security.user_already_exists')
            );
        }
        $this->createUser($poUser);

        // ==== Mise en session de l'utilisateur ====
        $this->setUserInSession($poUser);

        return $poUser;
    } // registerUser

    /**
     * Création d'un nouvel utilisateur
     *
     * @return User
     */
    public function createUser($poUser)
    {
        $loEncoder = $this->factory->getEncoder($poUser);
        $lsPassword = $loEncoder->encodePassword($poUser->getPassword(), $poUser->getSalt());
        $lsCountry = strtolower(substr($this->request->getLocale(), -2));
        $poUser->setPassword($lsPassword)
               ->setCountry($lsCountry);
        $this->manager->persist($poUser);
        $this->manager->flush();
    } // createUser
    
    /**
     * Crypte un password
     *
     * @return User
     */
    public function cryptPass($poUser, $psPass)
    {
        $loEncoder = $this->factory->getEncoder($poUser);
        $lsPassword = $loEncoder->encodePassword($psPass, $poUser->getSalt());
        return $lsPassword;
    } // createUser
}
