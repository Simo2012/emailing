<?php

namespace Web\WebBundle\Model\Contact;
use Web\WebBundle\Model\Tracking\TrackingChain;


/**
 * Auto Post sur twitter grâce au partage de réseau sociaux
 *
 * <pre>
 * Elias 10/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
class Twitter
{
    /**
     * Modèle de génération d'url de tracking
     *
     * @var AbstractTracking $tracking
     */
    private $tracking;

    /**
     * Constructeur, injection des dépendances
     *
     * @param \Web\WebBundle\Model\Tracking\TrackingChain $poTrackingChain
     */
    public function __construct(TrackingChain $poTrackingChain)
    {
        $this->tracking = $poTrackingChain;
    } // __constructeur

    /**
     * Retourne une URL permettant la publication sur Twitter
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     * @param \Web\WebBundle\Entity\Recommendation $poRecommendation
     * @return string
     */
    public function generateClickTagUrl($poOffer, $poRecommendation)
    {
        // ---- Url de tracking ----
        $loTracking = $this->tracking->getModel($poRecommendation->getOffer()->getPlatform());
        $lsLink = $loTracking->getClickTagUrl($poRecommendation);
        $lsTitle = $poOffer->getTitle();
        $lsUrl = "https://twitter.com/intent/tweet?url={$lsLink}&text={$lsTitle}"; // &via=rubizz_FR
                
        return $lsUrl;
    } // generateClickTagUrl
 }
