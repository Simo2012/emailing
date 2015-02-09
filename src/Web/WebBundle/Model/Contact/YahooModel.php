<?php
/**
 * Created by PhpStorm.
 * User: LAYTI_SARR
 * Date: 07/02/15
 * Time: 23:37
 */

namespace Site\AccountBundle\Model;

require_once __DIR__."/Yahoo/globals.php";
require_once __DIR__."/Yahoo/oauth_helper.php";

class YahooModel {

    private $consumer_key;
    private $consumer_secret;
    private $app_url;
    private $callback_url;
    private $app_id;
    private $request_token;
    private $request_token_secret;
    private $oauth_verifier;
    private $xoauth_request_auth_url;


    public function __construct($consumer_key, $consumer_secret, $app_url, $callback_domain, $app_id)
    {
        $this->consumer_secret = $consumer_secret;
        $this->consumer_key = $consumer_key;
        $this->app_url = $app_url;
        $this->callback_url = $callback_domain;
        $this->app_id = $app_id;

    }


    public function  setTokensAndUrl()
    {
        $response = get_request_token($this->consumer_key, $this->consumer_secret, $this->callback_url, false, true, true);
        if (!empty($response)) {
            list($info, $headers, $body, $body_parsed) = $response;
            if ($info['http_code'] == 200 && !empty($body)) {
                $this->request_token = $body_parsed['oauth_token'];
                $this->request_token_secret = $body_parsed['oauth_token_secret'];
                $this->xoauth_request_auth_url = $body_parsed['xoauth_request_auth_url'];
                $this->oauth_verifier = $body_parsed['oauth_token'];
//                echo  '<a href="'.urldecode($body_parsed['xoauth_request_auth_url']).'" >Yahoo Contact list</a>';
            }
        }
    }

    public function getContacts($request_token, $request_token_secret, $oauth_verifier)
    {
        $response = get_access_token_yahoo($this->consumer_key, $this->consumer_secret,
            $request_token, $request_token_secret, $oauth_verifier, false, true, true);
        if(!empty($response)) {
            list($info, $headers, $body, $body_parsed) = $response;
            if ($info['http_code'] == 200 && !empty($body)) {
                $guid    =  $body_parsed['xoauth_yahoo_guid'];
                $access_token  = rfc3986_decode($body_parsed['oauth_token']) ;
                $access_token_secret  = $body_parsed['oauth_token_secret'];
                $result = callcontact_yahoo($this->consumer_key, $this->consumer_secret, $guid, $access_token, $access_token_secret, false, true);
                return $result;
            }
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getConsumerKey()
    {
        return $this->consumer_key;
    }

    /**
     * @return mixed
     */
    public function getConsumerSecret()
    {
        return $this->consumer_secret;
    }

    /**
     * @return mixed
     */
    public function getOauthVerifier()
    {
        return $this->oauth_verifier;
    }

    /**
     * @return mixed
     */
    public function getRequestToken()
    {
        return $this->request_token;
    }

    /**
     * @return mixed
     */
    public function getRequestTokenSecret()
    {
        return $this->request_token_secret;
    }

    /**
     * @return mixed
     */
    public function getXoauthRequestAuthUrl()
    {
        return $this->xoauth_request_auth_url;
    }






}