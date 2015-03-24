<?php
namespace Web\WebBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Web\WebBundle\Entity\Recommendation;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Natexo\ToolBundle\Model\Filter\ApiEncryptFilter;
use Natexo\ToolBundle\Model\Filter\ApiDecryptFilter;

/**
 * Gestion des tags de tracking
 *
 * <pre>
 * Philippe 18/03/2015 Création
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package Rubizz
 */
class Tracking
{
    /**
     * Doctrine entity manager
     * @var EntityManager
     */
    private $manager;
    /**
     * La recommendation courante
     * @var Recommendation
     */
    private $recommendation;
    /**
     * Identifiant du contact si disponible
     * @var integer
     */
    private $contactId;
    /**
     * Objet de cryptage
     * @var ApiEncryptFilter
     */
    private $encrypt;
    /**
     * Objet de décryptage
     * @var ApiDecryptFilter
     */
    private $decrypt;
    /**
     * Indique que la commission peut être versée au client sinon c'est pour Rubizz
     * @var boolean
     */
    private $validOffer;


    /**
     * Constructeur, injection des paramètres
     *
     * @param ObjectManager $poManager
     * @param ApiEncryptFilter $poEncrypt
     * @param ApiDecryptFilter $poDecrypt
     */
    public function __construct(ObjectManager $poManager, ApiEncryptFilter $poEncrypt, ApiDecryptFilter $poDecrypt)
    {
        $this->manager = $poManager;
        $this->encrypt = $poEncrypt;
        $this->decrypt = $poDecrypt;
    } // __construct

    /**
     * Lecture de la recommendation
     *
     * @param integer $piRecommendationId Id de la recommendation
     * @return boolean True si la recommendation existe
     */
    public function readRecommendation($piRecommendationId)
    {
        $this->recommendation = $this->manager->getRepository('WebWebBundle:Recommendation')
                                     ->find($piRecommendationId);
        return !empty($this->recommendation);
    } // readRecommendation

    /**
     * Gère le tag d'ouverture
     *
     * @param string $psEmail Email du contact
     * @return bool
     */
    public function handleOpenTag($psEmail)
    {
        // ==== Création de la requête ====
        $this->contactId = $this->getContact($psEmail);
        $lsIp = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        $lsRequest = 'INSERT INTO RUBIZZ.track SET date_create = now(), date_update = now(), ';
        $lsRequest .= 'ip_address = :ip, recommendation_id = :recoid, date_open = now() ';
        if (!empty($this->contactId)) {
            $lsRequest .= ',contact_id = :contactid ';
        }
        $lsRequest .= 'ON DUPLICATE KEY UPDATE date_update = now(), ';
        $lsRequest .= 'date_open = if(date_open is null, now(), date_open) ';

        // ==== Exécution ====
        $loStmt = $this->manager->getConnection()->prepare($lsRequest);
        $laParams = array('ip' => $lsIp, 'recoid' => $this->recommendation->getId());
        if (!empty($this->contactId)) {
            $laParams['contactid'] = $this->contactId;
        }
        $loStmt->execute($laParams);

        return true;
    } // handleOpenTag

    /**
     * Gère le tag de clic
     *
     * @param string $psEmail Email du contact
     * @return bool
     */
    public function handleClickTag($psEmail)
    {
        // ==== Création de la requête ====
        $this->contactId = $this->getContact($psEmail);
        $lsIp = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        $lsRequest = 'INSERT INTO RUBIZZ.track SET date_create = now(), date_update = now(), ';
        $lsRequest .= 'ip_address = :ip, recommendation_id = :recoid, date_open = now() ';
        if (!empty($this->contactId)) {
            $lsRequest .= ',contact_id = :contactid ';
        }
        $lsRequest .= 'ON DUPLICATE KEY UPDATE date_update = now(), ';
        $lsRequest .= 'date_click = if(date_click is null, now(), date_click) ';

        // ==== Exécution ====
        $loStmt = $this->manager->getConnection()->prepare($lsRequest);
        $laParams = array('ip' => $lsIp, 'recoid' => $this->recommendation->getId());
        if (!empty($this->contactId)) {
            $laParams['contactid'] = $this->contactId;
        }
        $loStmt->execute($laParams);

        // ==== Création de la requête dans commission ====
        $loOffer = $this->recommendation->getOffer();
        if ('CPC' == $loOffer->getRemType()) {
            $this->validOffer = true;
            $this->writeCommission($lsIp);
        }

        return true;
    } // handleClickTag

    /**
     * Récupère l'URL de click de l'offre
     */
    public function getClickUrl()
    {
        $loOffer = $this->recommendation->getOffer();

        return $loOffer->getUrl();
    } // getClickUrl

    /**
     * Positionnement des cookies
     *
     * @param Response $poResponse Image vide pour le tag
     * @param string   $psEmail    Email du contact
     */
    public function writeCookies(Response $poResponse, $psEmail)
    {
        $loOffer = $this->recommendation->getOffer();
        $loDate = new \DateTime('now');
        $laParams = array(
            'recommendation_id' => $this->recommendation->getId(),
            'contact_id'        => $this->contactId,
            'date'              => $loDate->format('Ymd')
        );
        $loDate = new \DateTime('now');
        $loDate->add(new \DateInterval('P' . $loOffer->getMemberCookie() . 'D'));
        $loCookie = new Cookie('RbzOffer_' . $loOffer->getId(), $this->encrypt->filter($laParams), $loDate);
        $loDate->add(new \DateInterval('P' . $loOffer->getAdvertiserCookie() . 'D'));
        $loCookieNatexo = new Cookie('RbzNxoOffer_' . $loOffer->getId(), $this->encrypt->filter($laParams), $loDate);
        $poResponse->headers->setCookie($loCookie);
        $poResponse->headers->setCookie($loCookieNatexo);
    } // writeCookies

    /**
     * Lecture des cookies pour récupérer la recommendation
     *
     * @param Request $poRequest La requête active
     * @return bool
     */
    public function readCookies(Request $poRequest)
    {
        // ==== Lecture des cookies ====
        $liOfferId = $poRequest->get('operation');
        if (empty($liOfferId)) {
            return false;
        }
        // ---- Lecture du cookie membre ----
        $laParams = null;
        $this->validOffer = true;
        try {
            $lsCookie = $poRequest->cookies->get('RbzOffer_' . $liOfferId);
            $laParams = $this->decrypt->filter($lsCookie);
        } catch (\Exception $poException) {
        }
        // ---- Lecture du cookie Natexo ----
        if (empty($laParams)) {
            try {
                $lsCookie = $poRequest->cookies->get('RbzNxoOffer_' . $liOfferId);
                $laParams = $this->decrypt->filter($lsCookie);
                $this->validOffer = false;
            } catch (\Exception $poException) {
            }
        }
        if (empty($laParams)) {
            return false;
        }

        // ==== Lecture de la recommendation ====
        $this->recommendation = $this->manager->getRepository('WebWebBundle:Recommendation')
                                              ->find($laParams['recommendation_id']);
        $this->contactId = $laParams['contact_id'];
        if (empty($this->contactId)) {
            $this->contactId = null;
        }

        return true;
    } // readCookies

    /**
     * Gère le tag de lead
     *
     * @param string $psTransaction Identifiant de la transaction chez le marchand
     * @return bool
     */
    public function handleLeadTag($psTransaction)
    {
        // ==== Création de la requête dans track ====
        $lsIp = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        $lsRequest = 'INSERT INTO RUBIZZ.track SET date_create = now(), date_update = now(), ';
        $lsRequest .= 'ip_address = :ip, recommendation_id = :recoid, date_lead = now() ';
        if (!empty($this->contactId)) {
            $lsRequest .= ',contact_id = :contactid ';
        }
        $lsRequest .= 'ON DUPLICATE KEY UPDATE date_update = now(), ';
        $lsRequest .= 'date_lead = if(date_lead is null, now(), date_lead) ';

        // ==== Exécution ====
        $loStmt = $this->manager->getConnection()->prepare($lsRequest);
        $laParams = array('ip' => $lsIp, 'recoid' => $this->recommendation->getId());
        if (!empty($this->contactId)) {
            $laParams['contactid'] = $this->contactId;
        }
        $loStmt->execute($laParams);

        // ==== Création de la requête dans commission ====
        $loOffer = $this->recommendation->getOffer();
        if (in_array($loOffer->getRemType(), array('CPC', 'CPL'))) {
            $this->writeCommission($psTransaction);
        }

        return true;
    } // handleLeadTag

    /**
     * Gère le tag de vente
     *
     * @param string $psTransaction Identifiant de la transaction chez le marchand
     * @param string $psAmount Montant de la transaction
     * @return bool
     */
    public function handleSaleTag($psTransaction, $psAmount)
    {
        // ==== Création de la requête dans track ====
        $psAmount = $this->filterAmount($psAmount);
        $lsIp = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
        $lsRequest = 'INSERT INTO RUBIZZ.track SET date_create = now(), date_update = now(), ';
        $lsRequest .= 'ip_address = :ip, recommendation_id = :recoid, date_sale = now(), sale_amount = :amount ';
        if (!empty($this->contactId)) {
            $lsRequest .= ',contact_id = :contactid ';
        }
        $lsRequest .= 'ON DUPLICATE KEY UPDATE date_update = now(), ';
        $lsRequest .= 'sale_amount = if(date_sale is null, :amount, sale_amount), ';
        $lsRequest .= 'date_sale = if(date_sale is null, now(), date_sale) ';

        // ==== Exécution ====
        $loStmt = $this->manager->getConnection()->prepare($lsRequest);
        $laParams = array('ip' => $lsIp, 'recoid' => $this->recommendation->getId(), 'amount' => $psAmount);
        if (!empty($this->contactId)) {
            $laParams['contactid'] = $this->contactId;
        }
        $loStmt->execute($laParams);

        // ==== Création de la requête dans commission ====
        $loOffer = $this->recommendation->getOffer();
        if (in_array($loOffer->getRemType(), array('CPC', 'CPL', 'CPA', 'CPA%'))) {
            $this->writeCommission($psTransaction, $psAmount);
        }

        return true;
    } // handleSaleTag

    /**
     * Lecture du contact de l'utilisateur s'il existe
     *
     * @param string $psEmail Email du contact
     * @return null
     */
    protected function getContact($psEmail)
    {
        $liContactId = null;
        if (!empty($psEmail)) {
            $laParams = array('userId' => $this->recommendation->getUser(), 'email' => $psEmail);
            $loContact = $this->manager->getRepository('WebWebBundle:Contact')
                                       ->findOneBy($laParams);
            if (!empty($loContact)) {
                $liContactId = $loContact->getId();
            }
        }

        return $liContactId;
    } // getContact

    /**
     * Filtrage d'un montant renvoyé par un marchand
     *
     * @param string $psAmount Le montant
     * @return float
     */
    protected function filterAmount($psAmount)
    {
        $psAmount = strtr($psAmount, ',', '.');
        return floatval($psAmount);
    } // filterAmount

    /**
     * Calcul du montant récupéré par le client
     *
     * @param string $psAmount Le montant de la transaction donné par le site marchand
     * @return number
     */
    protected function getAmount($psAmount = '')
    {
        $loOffer = $this->recommendation->getOffer();
        $liAmount = 0;
        switch ($loOffer->getRemType()) {
            case 'CPC':
            case 'CPL':
            case 'CPA':
                $liAmount = $loOffer->getRemMember();
                break;
            case 'CPA%':
                $liAmount = $loOffer->getRemMember() / 100 * $psAmount;
                break;
        }

        return $liAmount;
    } // getAmount

    /**
     * Calcul du montant récupéré par Rubizz
     *
     * @param string $psAmount Le montant de la transaction donné par le site marchand
     * @return number
     */
    protected function getAmountRbz($psAmount = '')
    {
        $loOffer = $this->recommendation->getOffer();
        $liAmount = 0;
        switch ($loOffer->getRemType()) {
            case 'CPC':
            case 'CPL':
            case 'CPA':
                $liAmount = $loOffer->getRemAdvertiser();
                break;
            case 'CPA%':
                $liAmount = $loOffer->getRemAdvertiser() / 100 * $psAmount;
                break;
        }

        return $liAmount;
    } // getAmountRbz

    /**
     * Création d'une commission
     *
     * @param string $psTransaction Identifiant de la transaction chez le marchand
     * @param float|int $piAmount Le montant de l'achat
     */
    protected function writeCommission($psTransaction, $piAmount = 0)
    {
        // ==== Test du volume d'offres ====
        $loOffer = $this->recommendation->getOffer();
        if (!$loOffer->getRemIllimited() && (0 != $loOffer->getRemVolume())) {
            $liCount = $this->manager->getRepository('WebWebBundle:Commission')->countOffer($loOffer);
            if ($liCount >= $loOffer->getRemVolume()) {
                return;
            }
        }

        // ==== Création de la requête dans commission ====
        $lsRequest = 'INSERT INTO RUBIZZ.commission SET date_create = now(), recommendation_id = :recoid, ';
        $lsRequest .= ' amount = :amount, transaction = :transaction, amount_rbz = :amountRbz, valid_offer = :valid ';
        if (!empty($this->contactId)) {
            $lsRequest .= ',contact_id = :contactid ';
        }
        $lsRequest .= 'ON DUPLICATE KEY UPDATE amount = if(amount = 0, :amount, amount), ';
        $lsRequest .= 'amount_rbz = if(amount_rbz = 0, :amountRbz, amount_rbz) ';

        // ==== Exécution ====
        $loStmt = $this->manager->getConnection()->prepare($lsRequest);
        $laParams = array(
            'recoid'      => $this->recommendation->getId(),
            'transaction' => $psTransaction,
            'amount'      => $this->getAmount($piAmount),
            'amountRbz'   => $this->getAmountRbz($piAmount),
            'valid'       => $this->validOffer ? 1 : 0,
        );
        if (!empty($this->contactId)) {
            $laParams['contactid'] = $this->contactId;
        }
        $loStmt->execute($laParams);
    } // writeCommission
}
