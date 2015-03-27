<?php
namespace Web\WebBundle\Model\Tracking;

use Web\WebBundle\Entity\Offer;
use Doctrine\Common\Persistence\ObjectManager;
use Web\WebBundle\Entity\Recommendation;

/**
 * Génération des liens de tracking CAKE
 *
 * <pre>
 * Julien 27/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class CakeTracking extends AbstractTracking
{
    /**
     * Cake nécessite des paramètres statiques en fonction du pays
     *
     * @var array $staticParameters
     */
    private $staticParameters;

    /**
     * Injection des dépendances
     *
     * @param array $paTrackingData
     * @param array $paStaticParameters
     */
    public function __construct(array $paTrackingData, array $paStaticParameters)
    {
        parent::__construct($paTrackingData);
        $this->staticParameters = $paStaticParameters;
    } // __construct

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
        $laParameters = json_decode($loOffer->getPlatformId());

        // ==== Création de l'url ====
        // ---- Domaine ----
        $lsUrl = "http://{$this->trackingData['domain'][$loOffer->getCountry()]}/?";
        // ---- Paramètres statiques ----
        $lsUrl .= "a={$this->staticParameters[$loOffer->getCountry()]['affiliate_id']}&";
        // ---- Paramètres dynamiques ----
        $liCount = 1;
        foreach ($this->trackingData['parameters'] as $name => $parameter) {
            if (!empty($laParameters[$parameter])) {
                $lsUrl .= "{$parameter}={$laParameters[$parameter]}";
                if ($liCount < sizeof($this->trackingData['parameters'])) {
                    $lsUrl .= '&';
                }
                $liCount++;
            } else {
                throw new \Exception("A Cake url parameter is missing :  {$parameter} ({$name})");
            }
        }

        return $lsUrl;
    } // getOpenTagUrl

    /**
     * Retourne l'url de tracking de clic
     *
     * @param Recommendation $poRecommendation
     * @return string
     * @throws \Exception
     */
    public function getClickTagUrl(Recommendation $poRecommendation)
    {
        // ==== Initialisation ====
        $loOffer = $poRecommendation->getOffer();
        $laParameters = json_decode($loOffer->getPlatformId());

        // ==== Création de l'url ====
        // ---- Domaine ----
        $lsUrl = "http://{$this->trackingData['domain'][$loOffer->getCountry()]}/?";
        // ---- Paramètres statiques ----
        $lsUrl .= "a={$this->staticParameters[$loOffer->getCountry()]['affiliate_id']}&";
        // ---- Paramètres dynamiques ----
        $liCount = 1;
        foreach ($this->trackingData['parameters'] as $name => $parameter) {
            if (!empty($laParameters[$parameter])) {
                $lsUrl .= "{$parameter}={$laParameters[$parameter]}";
                if ($liCount < sizeof($this->trackingData['parameters'])) {
                    $lsUrl .= '&';
                }
                $liCount++;
            } else {
                throw new \Exception("A Cake url parameter is missing :  {$parameter} ({$name})");
            }
        }

        return $lsUrl;
    } // getClickTagUrl
}
