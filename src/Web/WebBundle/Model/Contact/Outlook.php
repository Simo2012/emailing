<?php

namespace Web\WebBundle\Model\Contact;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Doctrine\Common\Persistence\ObjectManager;
use Natexo\ToolBundle\Model\Filter\ApiDecryptFilter;
use Web\WebBundle\Model\Contact\Counter;

/**
 * Modèle permettant de récupérer la liste des contacts Outlook / Hotmail
 *
 * <pre>
 * Elias 08/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */

class Outlook extends Importer
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
     * Constructeur, injection des dépendances
     */
    public function __construct(
        RequestStack $poStack,
        Router $poRouter,
        ObjectManager $poManager,
        ApiDecryptFilter $poDecrypter,
        Counter $poCounter
    ) {
        parent::__construct($poManager, $poDecrypter, $poStack, $poCounter);
        $this->url = $poRouter->generate('OAuthOutlook', array(), true);
        switch ($this->getRequest()->getHost()) {
            case 'www.rubizz.fr':
                $lskey = '000000004C147831';
                $lsSecret = 'BHgcPhsPonYoGPxvr8VwZVDgFMY7OXkH';
                $lsUrl = 'http://www.rubizz.fr/authOutlook';
                break;
            case 'www.rubizz.us':
                $lskey = '000000004014EB43';
                $lsSecret = 'y-JAV7LoXx7Z3fxfh2iVbpiubxhzhkJe';
                $lsUrl = 'http://www.rubizz.us/authOutlook';
                break;
            default:
                $lskey = '000000004C147233';
                $lsSecret = 'YKssmSJ4P4VihF0xxwJM4PcDEedJkHL1';
                $lsUrl = 'http://rubizz.victor.natexo.com/app_dev.php/authOutlook';
                break;
        }
        $this->clientId = $lskey;
        $this->clientSecret = $lsSecret;
        $this->redirectUri = $lsUrl;
    } // __construct

    /**
     * Retourne un lien permettant l'acceptation du membre
     * @return string le lien de l'api
     */
    public function generateUrl()
    {
        $lsUrl = "https://login.live.com/oauth20_authorize.srf?";
        $lsUrl .= "client_id={$this->clientId}&scope=wl.signin%20wl.basic%20wl.emails%20wl.contacts_emails";
        $lsUrl .= "&response_type=code&redirect_uri={$this->redirectUri}";

        return $lsUrl;
    } // generateUrl

    /**
     * Récupere les contacts depuis l'api
     * @return array liste des contacts
     */
    public function getContactsFromApi()
    {
        // le code est généré grâce au lien cf generateUrl()
        if (isset($_GET["code"])) {
            $lsAccessToken = $this->getAccessToken();
            if (!empty($lsAccessToken)) {
                $lsUrl = 'https://apis.live.net/v5.0/me/contacts?access_token=' . $lsAccessToken . '&limit=5000';
                $loCurl = curl_init();
                curl_setopt($loCurl, CURLOPT_AUTOREFERER, TRUE);
                curl_setopt($loCurl, CURLOPT_HEADER, 0);
                curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($loCurl, CURLOPT_URL, $lsUrl);
                curl_setopt($loCurl, CURLOPT_FOLLOWLOCATION, TRUE);
                
                $loResultJson = curl_exec($loCurl);
                curl_close($loCurl);
                
                return json_decode($loResultJson, true);
            }
        }
    } // getContactsFromApi

    /**
     * Récupère le token de l'api
     * 
     */
    public function getAccessToken()
    {
        $lsUrl = 'https://login.live.com/oauth20_token.srf';
        $laFields = array(
            'code' => $_GET["code"],
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        );
        
        $lsData = http_build_query($laFields);
        $loCurl = curl_init();
        curl_setopt($loCurl, CURLOPT_URL, $lsUrl);
        curl_setopt($loCurl, CURLOPT_POST, 5);
        curl_setopt($loCurl, CURLOPT_POSTFIELDS, $lsData);
        curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($loCurl, CURLOPT_SSL_VERIFYPEER, 0);
        $loResultJson = curl_exec($loCurl);
        curl_close($loCurl);
        $laResponse = json_decode($loResultJson, true);

        return isset($laResponse['access_token']) ? $laResponse['access_token'] : '';
    } // getAccessToken
    
     /**
     * Récupère la liste des contacts formaté
      *
     * @return array les contacts
     */
    public function readContacts()
    {
        $laContacts = array();
        $laContactsList = $this->getContactsFromApi();
        if (count($laContactsList) > 0) {
            foreach ($laContactsList['data'] as $laContact) {
                $this->addContact(array(
                    'firstname' => trim($laContact['first_name']),
                    'lastname'  => trim($laContact['last_name']),
                    'email'     => trim($laContact['emails']['preferred'])
                ));
            }
        }
    } //readContacts
}
