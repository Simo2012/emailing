<?php

namespace Web\WebBundle\Model\Contact;

use Web\WebBundle\Entity\Offer;
use Web\WebBundle\Entity\User;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Auto Post sur facebook grâce au partage de réseau sociaux
 *
 * <pre>
 * Elias 10/02/2015 Création
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
     *
     * @var string id api 
     */
    private $clientSecret;

    /**
     *
     * @var string url de redirection interne
     */
    private $redirectUri;

    /**
     * FB redirige vers cette page avec le code en GET
     * @var object Helper facebook
     */
    private $helper;

    /**
     * Constructeur, injection des dépendances
     */
    public function __construct(array $paramsApi)
    {
        $this->clientId = $paramsApi['facebook']['client_id'];
        $this->clientSecret = $paramsApi['facebook']['client_secret'];
        $this->redirectUri = $paramsApi['facebook']['redirect_uri'];

        FacebookSession::setDefaultApplication($this->clientId, $this->clientSecret);
        $this->helper = new FacebookRedirectLoginHelper($this->redirectUri);
    }// __constructeur

    /**
     * Poste vers facebook
     *
     * @param \Web\WebBundle\Entity\Offer $poOffer
     */
    public function autoPost(Offer $poOffer)
    {
        $loSession = $this->generateFacebookSession();
        //si il y a la session FB existante
        if ($loSession) {
            //=== initialisation variable ===
            $lsPathPicture = "http://img.enqueteetselonvous.com/RBZ/";
            $lsCountry = $poOffer->getCountry();
            $lsPicture = $lsPathPicture . strtoupper($lsCountry) . "/RUBIZZ/50/bg600.jpg";

            //=== postage sur le mur du user
            try {
                $loResponse = (new FacebookRequest(
                        $loSession, 'POST', '/me/feed', array(
                    "message" => $poOffer->getSubtitle(),
                    "link" => $poOffer->getUrl(),
                    "picture" => $lsPicture,
                    "name" => $poOffer->getTitle(),
                    "caption" => $poOffer->getUrl(),
                    "description" => $poOffer->getTitle()
                        )
                        ))->execute()->getGraphObject();

                return $loResponse->getProperty('id');
            } catch (\Exception $poException) {
                error_log($poException);
            }
        } else {
            //Permissions requises
            $lsRequiredScope = 'public_profile, publish_actions, email';
            $lsLoginUrl = $this->helper->getLoginUrl(array('scope' => $lsRequiredScope));
            echo '<a href="' . $lsLoginUrl . '">Login with Facebook</a>';
        }
    }//autopost

    /**
     * Retourne la session fb si elle existe
     *
     */
    public function generateFacebookSession()
    {
        $loSession = false;

        //tentative de recuperation de la session courante de l'user
        try {
            $loSession = $this->helper->getSessionFromRedirect();
        } catch (FacebookRequestException $poException) {
            error_log($poException);
        } catch (\Exception $poException) {
            error_log($poException);
        }
        return $loSession;
    }//generateFacebookSession

    /**
     * Sauvegarde des informations user
     * 
     */
    private function saveDetailsUser($poUser)
    {
        // enregistrement boolean dans entité user
    }//saveDetailsUser

}
