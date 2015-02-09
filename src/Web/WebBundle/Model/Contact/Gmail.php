<?php

namespace Web\WebBundle\Model\Contact;

use ArrayIterator;

/**
 * Modéle permettant de récuperer la liste des contacts gmail
 *
 * <pre>
 * Mohammed 08/02/2015 Création
 * </pre>
 * @author Mohammed
 * @version 1.0
 * @package Web
 */
class Gmail extends ArrayIterator {

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
    public function __construct() {
        $this->clientId = '1086839973461-3khcu8rfsbn9ailo003qc270osd2rjqf.apps.googleusercontent.com';
        $this->clientSecret = '6dgNwrD2yDCE_n0b_BIavjnu';
        $this->redirectUri = 'http://rubizz.mohammed.natexo.com';
    }// __constructeur

    /**
     * Return un lien permettant l'autorisation du membre
     *
     */
    public function generateUrl() {
        $lsUrl = 'https://accounts.google.com/o/oauth2/auth?client_id=' .
                $this->clientId . '&redirect_uri=' . $this->redirectUri .
                '&scope=https://www.google.com/m8/feeds/&response_type=code';
        return $lsUrl;
    }//generateUrl
    
    /**
     * 
     * @param type $locode
     * @return type
     */
    public function getToken($locode) {
        $laFields = array(
            'code' => urlencode($locode),
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

        $locurl = curl_init();
        curl_setopt($locurl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($locurl, CURLOPT_POST, 5);
        curl_setopt($locurl, CURLOPT_POSTFIELDS, $lsFields);
        curl_setopt($locurl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($locurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($locurl, CURLOPT_SSL_VERIFYHOST, 0);
        $laresult = curl_exec($locurl);
        curl_close($locurl);

        return json_decode($laresult);
    }//getToken

    /**
     * function pour retourner less contacts 
     * 
     */
    public function getContacts() {

        if (isset($_GET["code"])) {


            $lsaccesstoken = $this->getToken($_GET["code"])->access_token;
            $lsurl = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=120&oauth_token=' . $lsaccesstoken;
            $lsxmlresponse = $this->getContents($lsurl);
            if ((strlen(stristr($lsxmlresponse, 'Authorization required')) > 0) && (strlen(stristr($lsxmlresponse, 'Error ')) > 0)) {
                return null;
            }
            $loxml = new \SimpleXMLElement($lsxmlresponse);
            $loxml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
            $this->putContact($loxml);
        }
    }//getContacts
    
    /**
     * 
     * @param type $loxml
     */
    public function putContact($loxml) {
        $cp = 0;
        foreach ($loxml->entry as $key) {
            foreach ($key->xpath('gd:email') as $email) {

                if ((string) $key->title == "") {
                    $this[$cp]['name'] = "No Name";
                    
                } else {
                    $this[$cp]['name'] = (string) $key->title;
                }
                $this[$cp]['email'] = $email->attributes()->address;
                $cp++;
            }
            
       
        }
    }//putContact
    
    /**
     * 
     * @param type $lsurl
     * @return type
     */
    public function getContents($lsurl) {
        $locurl = curl_init();
        $louserAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($locurl, CURLOPT_URL, $lsurl); //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($locurl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($locurl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.	

        curl_setopt($locurl, CURLOPT_USERAGENT, $louserAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($locurl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($locurl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($locurl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($locurl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($locurl, CURLOPT_SSL_VERIFYHOST, 0);

        $locontents = curl_exec($locurl);
        curl_close($locurl);
        return $locontents;
    }//getContent

    /**
     * 
     */
    public function readContacts() {
        $this->getContacts();

        if ($this != null) {
            for ($i = 0; $i < $this->count(); $i++)
                echo 'email contact : ' . $this[$i]['email'] .
                ' =====> nom contact ' . $this[$i]['name'] . ' <br>';
        }


        exit;
    }//readContacts

// test
}
