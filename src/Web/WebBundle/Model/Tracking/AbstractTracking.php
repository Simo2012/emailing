<?php
namespace Web\WebBundle\Model\Tracking;

use Web\WebBundle\Entity\Contact;
use Web\WebBundle\Entity\Offer;
use Symfony\Component\Console\Output\OutputInterface;
use Web\WebBundle\Entity\Recommendation;
use Web\WebBundle\Model\Tracking\TrackingChain;

/**
 * Classe abstraite commune aux modèles de générations d'urls de tracking
 *
 * <pre>
 * Julien 26/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
abstract class AbstractTracking
{
    /**
     * Tableau des paramètres d'url
     *
     * @var array
     */
    protected $trackingData;

    /**
     * Booléen dédié au ManualTracking
     *
     * @var bool withEmail
     */
    protected $withEmail = false;

    /**
     * Contact à lire pour récupérer l'email,
     * dédié au Manual Tracking
     *
     * @var Contact $contact
     */
    protected $contact;

    /**
     * Initialisations
     *
     * @param array $paTrackingData
     */
    public function __construct(array $paTrackingData)
    {
        $this->trackingData  = $paTrackingData;
    } // __construct

    /**
     * Retourne l'url de tracking d'ouverture
     *
     * @param Recommendation $poRecommendation
     * @return string
     */
    abstract public function getOpenTagUrl(Recommendation $poRecommendation);

    /**
     * Retourne l'url de tracking de clic
     *
     * @param Recommendation $poRecommendation
     * @return string
     */
    abstract public function getClickTagUrl(Recommendation $poRecommendation);

    /**
     * @param $pbWithEmail
     */
    public function setWithEmail($pbWithEmail)
    {
        $this->withEmail = $pbWithEmail;
    } // setWithEmail

    /**
     * Set contact
     *
     * @param Contact $contact
     * @return AbstractTracking
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    } // setContact
}
