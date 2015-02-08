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
     * array paramsFacebook
     * @var bundle
     */
    private $clientId = '1086839973461-3khcu8rfsbn9ailo003qc270osd2rjqf.apps.googleusercontent.com';
    private $clientSecret = '6dgNwrD2yDCE_n0b_BIavjnu';
    private $redirectUri = 'http://rubizz.mohammed.natexo.com';

    /**
     * Constructeur, injection des dépendances
     */
    public function __construct() {
        
    }

// __constructeur

    /**
     * Return un lien permettant l'autorisation du membre
     *
     */
    public function generateUrl() {
        $lsUrl = 'https://accounts.google.com/o/oauth2/auth?client_id=' .
                $this->clientId . '&redirect_uri=' . $this->redirectUri .
                '&scope=https://www.google.com/m8/feeds/&response_type=code';
        return $lsUrl;
    }

//generateUrl

    /**
     * Sauvegarde
     * 
     */
    public function getContacts() {
        if (isset($_GET["code"])) {
            $auth_code = $_GET["code"];
            $fields = array(
                'code' => urlencode($auth_code),
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => urlencode('authorization_code')
            );
            $post = '';
            foreach ($fields as $key => $value) {
                $post .= $key . '=' . $value . '&';
            }
            $post = rtrim($post, '&');

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
            curl_setopt($curl, CURLOPT_POST, 5);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            $result = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($result);
            $accesstoken = $response->access_token;
            $url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=120&oauth_token=' . $accesstoken;
            $xmlresponse = $this->curl_file_get_contents($url);
            if ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0)) {
                echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
                exit();
            }
            echo "<h3>Email Addresses:</h3>";
            //echo $xmlresponse;
            $xml = new \SimpleXMLElement($xmlresponse);
            $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
            $result = $xml->xpath('//gd:email');
            $i = 0;
            foreach ($result as $title) {
                //echo $title->attributes()->address . "<br>";
                $this[$i]['email'] = $title->attributes()->address;
                //$this->append('email'$title->attributes()->address);
                // $this->next();
                $i++;
            }
        }
    }
    //getContacts

    public function curl_file_get_contents($url) {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.	

        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }

    public function readContacts() {
        $laContacts = $this->getContacts();

        if ($this != null) {
            var_dump($laContacts);
            for ($i = 0; $i < $this->count(); $i++)
                echo 'email contact : ' . $this[$i]['email'] . '<br>';
        }

        exit;
    }

    /**
     * Sauvegarde
     * 
     */
    public function saveContact($paInfoContact) {
        $loContact = new \Web\WebBundle\Entity\Contact;
        $loContact->setFirstname('');
        $loContact->setLastname();
        $loContact->setEmail();

        //array_unique($laContact['emails']);
        //var_dump($laContact);
        exit;
    }

// test
}
