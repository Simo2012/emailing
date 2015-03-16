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
     * Retourne une URL permettant la publication sur Twitter
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     * @return string
     */
    public function generateUrl($poOffer)
    {
        $lsLink  = $poOffer->getUrl();
        $lsTitle = $poOffer->getTitle();
        $lsUrl   = "https://twitter.com/intent/tweet?url={$lsLink}&text={$lsTitle}"; // &via=rubizz_FR
                
        return $lsUrl;
    } // generateUrl
 }
