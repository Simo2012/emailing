<?php

namespace Web\WebBundle\Model\Contact;

use Web\WebBundle\Model\Tracking\TrackingChain;


/**
 * Auto Post sur facebook grâce au partage de réseau sociaux
 *
 * <pre>
 * Elias 13/03/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
class Facebook
{
    /**
     * Modèle de génération d'url de tracking
     *
     * @var AbstractTracking $tracking
     */
    private $tracking;

    /**
     * Paramètres API Facebook
     *
     * @var array $paramsApi
     */
    private $paramsApi;


    /**
     * Constructeur, injection des dépendances
     *
     * @param TrackingChain $poTrackingChain
     * @param array $paramsApi
     */
    public function __construct(TrackingChain $poTrackingChain, array $paramsApi)
    {
        $this->tracking  = $poTrackingChain;
        $this->paramsApi = $paramsApi['facebook'];
    } // __constructeur


    /**
     * Retourne un lien permettant le postage sur facebook
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     * @param \Web\WebBundle\Entity\Recommendation $poRecommendation
     * @param string $psLocale
     * @return string
     */
    public function generateClickTagUrl($poOffer, $poRecommendation, $psLocale)
    {
        // ==== Initialisations ====
        // ---- Url de tracking ----
        $liRecommendationId = $poRecommendation->getId();
        $loTracking = $this->tracking->getModel($poRecommendation->getOffer()->getPlatform());
        $lsLink = $loTracking->getClickTagUrl($poRecommendation);
        $lsDesc = $poOffer->getTitle();
        $lsOfferId = $poOffer->getId();
        $lsLocale = strtolower(substr($psLocale, -2, 2));
        $lsPicture = "http://img.rubizz.".$lsLocale."/RUBIZZ/OFFERS/IMAGES/{$lsOfferId}.jpg";
        $lsFbClientId = $this->paramsApi[$lsLocale]['client_id'];

        // ---- Url Facebook ----
        $lsUrl = "https://www.facebook.com/dialog/feed?";
        $lsUrl .="app_id={$lsFbClientId}";
        $lsUrl .="&display=popup&caption={$lsDesc}";
        $lsUrl .="&link={$lsLink}";
        $lsUrl .="&picture={$lsPicture}";
        $lsUrl .="&redirect_uri=http://rubizz.{$lsLocale}/{$psLocale}";
        $lsUrl .="/recommendation/addRecommendationByFacebook/{$lsOfferId}/{$liRecommendationId}";

        return $lsUrl;
    } // generateClickTagUrl

 }
