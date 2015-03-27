<?php

namespace Web\WebBundle\Model\Contact;


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
     *
     * @var string id api 
     */
    private $clientId;

    
    /**
     * Constructeur, injection des dépendances
     */
    public function __construct(array $paramsApi)
    {
        $this->clientId = $paramsApi['facebook']['client_id'];
    } // __constructeur


    /**
     * Retourne un lien permettant le postage sur facebook
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     * @param \Web\WebBundle\Entity\Recommendation $poRecommendation
     * @param string $psLocale
     * @param string $psFrom
     * @return string
     */
    public function generateUrl($poOffer, $poRecommendation, $psLocale, $psFrom)
    {
        // ==== Initialisations ====
        // ---- Url de tracking ----
        $liRecommendationId = $poRecommendation->getId();
        $lsLink = "http://rubizz.anis.natexo.com/app_dev.php/track/click/{$liRecommendationId}";
        $lsDesc = $poOffer->getTitle();
        $lsOfferId = $poOffer->getId();
        $lsLocale = strtolower(substr($psLocale, -2, 2));
        $lsPicture = "http://img.rubizz.".$lsLocale."/RUBIZZ/OFFERS/IMAGES/{$lsOfferId}.jpg";
        // ---- Url Facebook ----
        $lsUrl = "https://www.facebook.com/dialog/feed?";
        $lsUrl .="app_id={$this->clientId}";
        $lsUrl .="&display=popup&caption={$lsDesc}";
        $lsUrl .="&link={$lsLink}";
        $lsUrl .="&picture={$lsPicture}";
        $lsUrl .="&redirect_uri=http://rubizz.{$lsLocale}/{$psLocale}";
        $lsUrl .="/recommendation/addRecommendationByFacebook/{$lsOfferId}/{$psFrom}/{$liRecommendationId}";

        return $lsUrl;
    } //generateUrl 

 }
