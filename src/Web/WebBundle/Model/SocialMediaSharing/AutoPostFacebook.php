<?php

namespace Web\WebBundle\Model\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\CurlHttpClient;
use Facebook\GraphUser; 

/**
 * Auto Post sur facebook
 *
 * <pre>
 * Elias 05/02/2015 Création
 * </pre>
 * @author Elias
 * @version 1.0
 * @package Web
 */
class SocialMediaSharing
{

    private $paramsFacebook;
    private $kernel;

    /**
     * Constructeur, injection des dépendances
     */
    public function __construct(array $paParamsFacebook, $poKernel)
    {
        $this->paramsFacebook = $paParamsFacebook;
        $this->kernel = $poKernel;
    } // __constructeur
    
    /**
     * Poste vers facebook
     *
     */
    public function autoPost()
    {
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        // verification du kernel environnement
        /*if ($this->kernel == 'dev') {
            return false;
        }*/
     //session_start();
        $laConfig = array();
        $laConfig['appId'] = $this->paramsFacebook['fr']['appid'];
        $laConfig['secret'] = $this->paramsFacebook['fr']['secret'];
        
        FacebookSession::setDefaultApplication($laConfig['appId'], $laConfig['secret']);

$helper = new FacebookRedirectLoginHelper('http://rubizz.anis.natexo.com/app_dev.php/hello/poupone');
if(isset($_GET['logout'])) {
unset($_SESSION['fb_token']);
}
// enregistrement du token facebook
if(isset($_SESSION) && isset($_SESSION['fb_token'])) {
$session = new FacebookSession($_SESSION['fb_token']);
try {
if(!$session->validate()) $session = null;
} catch (Exception $e) {
$session = null;
}
} else {
try {
$session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
echo $ex->message; // Facebook exception
} catch( Exception $ex ) {
echo $ex->message; // Other exception
}
}
// si connecté
if(isset($session)) {
/*$_SESSION['fb_token'] = $session->getToken();
$session = new FacebookSession( $session->getToken() );
$request = new FacebookRequest($session, 'GET', '/me');
$response = $request->execute();
$graphObject = $response->getGraphObject()->asArray();
// infos accessibles
var_dump($graphObject);
echo '<a href="' . $helper->getLogoutUrl($session, 'http://sdkv4.local?logout=true') . '">Logout</a>';*/
  try {

    $response = (new FacebookRequest(
      $session, 'POST', '/me/feed', array(
        'link' => 'www.example.com',
        'message' => 'User provided message'
      )
    ))->execute()->getGraphObject();

    echo "Posted with id: " . $response->getProperty('id');

  } catch(FacebookRequestException $e) {

    echo "Exception occured, code: " . $e->getCode();
    echo " with message: " . $e->getMessage();

  }
} else {
// nouvelles façon de déclarer les "scopes" 'scope' =>'publish_stream,manage_pages'
//echo '<a href="' . $helper->getLoginUrl( array( 'email', 'user_friends', 'publish_stream', 'manage_pages' ) ) . '">Login</a>';

                    echo '<a href="'.$helper->getLoginUrl(
                    array(
                    'scope' =>'publish_stream,manage_pages' 
                    )
                ).'">connect</a>';
    
} 
        
        
        
    }// autoPost

    /**
     * Poste vers facebook
     *
     * @param \Admin\SurveyBundle\Entity\Survey $poSurvey
     */
    /*public function autoPost(Survey $poSurvey)
    {
        // définition du pays
        $lsCountry = $poSurvey->getCountry();
        
        // on vérifie que le compte facebook existe
        if ($this->isFacebookCountryExist($lsCountry)) {
            $laConfig = array();
            $laConfig['appId'] = $this->paramsFacebook[$lsCountry]['appid'];
            $laConfig['secret'] = $this->paramsFacebook[$lsCountry]['secret'];

            $fb = new \Facebook($laConfig);
            $lsPathPicture = "http://img.enqueteetselonvous.com/SRV/";
            $lsPicture = $lsPathPicture . strtoupper($lsCountry) . "/SURVEY{$poSurvey->getId()}/bg600.jpg";
            $laParams = array(
                "access_token" => $this->paramsFacebook[$lsCountry]['token'],
                "message" => $poSurvey->getDisplayname(),
                "link" => $poSurvey->getUrl(),
                "picture" => $lsPicture,
                "name" => $poSurvey->getBaseline(),
                "caption" => $poSurvey->getUrl(),
                "description" => $poSurvey->getOptinsentence()
            );
            
            try {
                $fb->api('/'.$this->paramsFacebook[$lsCountry]['pageid'].'/feed', 'POST', $laParams);
            } catch (\Exception $poException) {
                var_dump($poException);
            }
        }
    }// autoPost*/
}
