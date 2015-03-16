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
     * Return un lien permettant le postage sur facebook
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     */
    public function generateUrl($poOffer, $psLocale, $psFrom)
    {
        // ==== Initialisations ====
        $lsLink = $poOffer->getUrl();
        $lsDesc = $poOffer->getTitle();
        $lsPathPicture = "http://img.enqueteetselonvous.com/RBZ/";
        $lsCountry = $poOffer->getCountry();
        $lsPicture = $lsPathPicture . strtoupper($lsCountry) . "/RUBIZZ/50/bg600.jpg";
        $lsOfferId = $poOffer->getId();

        $lsUrl = "https://www.facebook.com/dialog/feed?";
        $lsUrl .="app_id={$this->clientId}";
        $lsUrl .="&display=popup&caption={$lsDesc}";
        $lsUrl .="&link={$lsLink}";
        $lsUrl .="&picture={$lsPicture}";
        $lsUrl .="&redirect_uri=http://rubizz.anis.natexo.com/app_dev.php/{$psLocale}";
        $lsUrl .="/recommendation/addRecommendationByFacebook/{$lsOfferId}/{$psFrom}";

        return $lsUrl;
    } //generateUrl

 }
