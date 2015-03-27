<?php

namespace Web\WebBundle\Model\Contact;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;
use Web\WebBundle\Model\Contact\Importer;
use Doctrine\Common\Persistence\ObjectManager;
use Natexo\ToolBundle\Model\Filter\ApiDecryptFilter;

/**
 * Modèle permettant de récupérer la liste des contacts gmail
 *
 * <pre>
 * Mohammed 08/02/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
class Gmail extends Importer
{

    /**
     * Permet de recuperer les codes api
     * array GMAIL
     * @var bundle
     */
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    /**
     * Constructeur, injection des dépendances
     */
    public function __construct(RequestStack $poStack, Router $poRouter, ObjectManager $poManager, ApiDecryptFilter $poDecrypter) {
        parent::__construct($poManager, $poDecrypter, $poStack);
        $this->clientId = '1063487764464-c3qg16aa50tb0bm1livj7siialgqq26u.apps.googleusercontent.com';
        $this->clientSecret = '10hywwKk5PSDoTF9aa6ODoTp';
        $this->redirectUri = $poRouter->generate('OAuthGoogle', array(), true);
    }// __constructeur


    /**
     * Return un lien permettant l'autorisation du membre
     * 
     * generateUrl 
     * @return string
     */
    public function generateUrl() {
        $lsUrl  =  'https://accounts.google.com/o/oauth2/auth?client_id=';
        $lsUrl .=  $this->clientId . '&redirect_uri=' . $this->redirectUri;
        $lsUrl .=  '&scope=https://www.google.com/m8/feeds/&response_type=code';
        return $lsUrl;
    } // generateUrl

    /**
     * Permet d'avoir le token pour accéder a list des contacts
     *
     * @param string $lsCode
     */
    public function setToken($lsCode) {
        $laFields = array(
            'code' => urlencode($lsCode),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => urlencode('authorization_code')
        );
        $lsFields = '';
        foreach ($laFields as $key => $value) {
            $lsFields .= $key . '=' . $value . '&';
        }
        $lsFields = rtrim($lsFields, '&');

        $loCurl = curl_init();
        curl_setopt($loCurl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($loCurl, CURLOPT_POST, 5);
        curl_setopt($loCurl, CURLOPT_POSTFIELDS, $lsFields);
        curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($loCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($loCurl, CURLOPT_SSL_VERIFYHOST, 0);
        $lsResponse = curl_exec($loCurl);
        curl_close($loCurl);
        $laResult = json_decode($lsResponse, true);
        $this->token = $laResult['access_token'];
    } // setToken

    /**
     * Accés à liste des contacts
     */
    public function readContacts()
    {
        if (!empty($this->token)) {
            $lsurl = 'https://www.google.com/m8/feeds/contacts/default/full'
                .'?v=3.0&max-results=10000&oauth_token=' . $this->token;
            $lsXmlResponse = $this->getContents($lsurl);
            if ((strlen(stristr($lsXmlResponse, 'Login required')) > 0) && (strlen(stristr($lsXmlResponse, 'Error')) > 0)) {
                return false;
            }
            $loXml = new \SimpleXMLElement($lsXmlResponse);
            $loXml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
            foreach ($loXml->entry as $loEntry) {
                foreach ($loEntry->xpath('gd:email') as $loEmail) {
                    $laContact = array();
                    // if (empty($loEntry->title)) {
                    //     $laContact['name'] = "No Name";
                    // } else {
                    //     $laContact['name'] = (string) $loEntry->title;
                    // }
                    $laContact['email'] = (string) $loEmail->attributes()->address;
                    $this->addContact($laContact);
                }
            }
        }
    } // readContacts
    
    /**
     * Retourne le contenu des listes des contact
     *
     * @param string $psUrl
     * @return string
     */
    public function getContents($psUrl) {
        $loCurl = curl_init();
        $loUserAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($loCurl, CURLOPT_URL, $psUrl); //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($loCurl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($loCurl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.

        curl_setopt($loCurl, CURLOPT_USERAGENT, $loUserAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($loCurl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($loCurl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($loCurl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($loCurl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($loCurl, CURLOPT_SSL_VERIFYHOST, 0);

        $lsResponse = curl_exec($loCurl);
        curl_close($loCurl);

        return $lsResponse;
    } // getContent
}
