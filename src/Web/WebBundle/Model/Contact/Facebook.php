<?php

namespace Web\WebBundle\Model\Contact;


/**
 * Auto Post sur facebook grâce au partage de réseau sociaux
 *
 * <pre>
 * Elias 05/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
class Facebook
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
     */
    public function generateUrl($poOffer, $poUser)
    {
        $lsHref='https://developers.facebook.com/docs/plugins/';
        $lsAppId='1544677199143364';
        $lsPicture = $poOffer->getImage();
        $lsName = $poOffer->getName();
        $lsCaption = $poOffer->getName();
        $lsDesc = $poOffer->getDescription();
        
        $lsUrl = "http://www.facebook.com/plugins/share_button.php?";
        $lsUrl .= "href={$lsHref}&layout=button&appId={$lsAppId}&picture={$lsPicture}";
        $lsUrl .= "&name={$lsName}&caption={$lsCaption}&description={$lsDesc}";
        
        // enregistrement des informations client
        $this->saveDetailsUser($poUser);
        
        return $lsUrl;
    } //generateUrl
    
    /**
    * Sauvegarde
    * 
    */
    private function saveDetailsUser($poUser)
    {
        // enregistrement boolean dans entité user
    }

    } // saveDetailsUser
