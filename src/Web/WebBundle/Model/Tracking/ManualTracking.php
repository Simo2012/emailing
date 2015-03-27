<?php
namespace Web\WebBundle\Model\Tracking;

use Web\WebBundle\Entity\Offer;
use Doctrine\Common\Persistence\ObjectManager;
use Web\WebBundle\Entity\Recommendation;

/**
 * Génération des liens de tracking Rubizz
 *
 * <pre>
 * Julien 27/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class ManualTracking extends AbstractTracking
{
    /**
     * Retourne l'url de tracking d'ouverture
     *
     * @param Recommendation $poRecommendation
     * @return string
     * @throws \Exception
     */
    public function getOpenTagUrl(Recommendation $poRecommendation)
    {
        // ==== Initialisation ====
        $loOffer = $poRecommendation->getOffer();

        // ==== Création de l'url ====
        // ---- Domaine ----
        $lsUrl = "http://www.{$this->trackingData['domain'][$loOffer->getCountry()]}/track/open/";
        // ---- Paramètres ----
        $lsUrl .= "{$poRecommendation->getId()}&email={$this->contact->getEmail()}";

        return $lsUrl;
    } // getOpenTagUrl

    /**
     * Retourne l'url de tracking de clic
     *
     * @param Recommendation $poRecommendation
     * @return string
     */
    public function getClickTagUrl(Recommendation $poRecommendation)
    {
        // ==== Initialisation ====
        $loOffer = $poRecommendation->getOffer();

        // ==== Création de l'url ====
        // ---- Domaine ----
        $lsUrl = "http://www.{$this->trackingData['domain'][$loOffer->getCountry()]}/track/click/";
        // ---- Paramètres ----
        $lsUrl .= "{$poRecommendation->getId()}";
        if ($this->withEmail) {
            $lsUrl .= "&email={$this->contact->getEmail()}";
        }

        return $lsUrl;
    } // getClickTagUrl
}
