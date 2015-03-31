<?php

namespace Web\WebBundle\Model\User;

use GeoIp2\Database\Reader;

/**
 * Détection du pays d'un visiteur par ip geoip
 *
 * <pre>
 * Elias 30/03/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
class DetectCountryUser
{
    
    /**
     * Path du dir courant
     * @var rootDir
     */
    private $rootDir;

    /**
     * route de la base geoip
     * @var string
     */
    private $pathDatabase;

    
    /**
     * Constructeur, injection des dépendances
     * 
     * @param type $psRootDir
     * @param type $psPathDatabase
     */
    public function __construct($psRootDir, $psPathDatabase)
    {
        $this->rootDir = $psRootDir;
        $this->pathDatabase = $psPathDatabase;
    }// __construct

    /**
     * appel à geoip pour recuperer le pays selon l'ip
     * 
     * @param type $psCountryOffer
     * @return type
     */
    public function getCountryByIp($psCountryOffer)
    {
        // ==== recuperation du path de la base données geoip ====
        $lsFilename = dirname($this->rootDir) . $this->pathDatabase;

        // ---- appel a geoip pour recuperer le code pays ----
        // ---- pas de service, obliger de faire un new ----
        
        $lsIpUser = $_SERVER['REMOTE_ADDR'];
        // ---- Test sur l'ip natexo FR ----
        if($lsIpUser != '62.23.113.242'){
            try {
            $loReader = new Reader($lsFilename);
            $loCountryByIp = $loReader->country($lsIpUser);

            $lsCountryCode = $loCountryByIp->country->isoCode;
            } catch (\Exception $e) {
                trigger_error('Erreur geoip : ' . $e->getMessage());
                return;
            }
            if (!empty($lsCountryCode)) {
                return $this->checkCountry($psCountryOffer, $lsCountryCode);
            }
        }
    } // getCountryByIp
    
    /**
     * on verifie que le pays de l'offre correspond au pays du visiteur
     * 
     * @param type $psCountryCodeOffer
     * @param type $psCountryCodeByIp
     * @return type
     */
    public function checkCountry($psCountryCodeOffer, $psCountryCodeByIp)
    {
        // ==== on verifie que le pays de l'offre correspond au pays du visiteur ====
        if (strtolower($psCountryCodeOffer) != strtolower($psCountryCodeByIp)) {
            return false;
        }
        return true;
    } // checkCountry

}
