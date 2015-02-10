<?php

namespace Web\WebBundle\Model\Contact;


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
     * Constructeur, injection des dépendances
     */
    public function __construct()
    {
    } // __constructeur
    
    /**
     * Return un lien permettant le postage sur facebook
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     */
    public function generateUrl($poOffer)
    {
        $lsLink = $poOffer->getUrl();
        $lsDesc = $poOffer->getTitle();
        
        $lsUrl = "https://twitter.com/share?";
        $lsUrl .= "url={$lsLink}&via=rubizz&text={$lsDesc}";
                
        return $lsUrl;
    } //generateUrl
    
    /**
    * Sauvegarde
    * 
    * @param \Web\WebBundle\Entity\User $poUser
    */
    public function saveDetailsUser($poUser)
    {
        // enregistrement boolean dans entité user
        $poUser->setUseTwitter(true);
    } // saveDetailsUser

 }
