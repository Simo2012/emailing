<?php
namespace Web\WebBundle\Model\Contact;

use  Web\WebBundle\Model\Contact\Util;

Class YahooModel extends Util
{
    private $consumer_key;
    private $consumer_secret;

    public function __construct ($Key, $Secret)
    {
        $this->consumer_key = $Key;
        $this->consumer_secret = $Secret;
    }
    /**
     * Do an HTTP GET
     * @param string $url
     * @param int $port (optional)
     * @param array $headers an array of HTTP headers (optional)
     * @return array ($info, $header, $response) on success or empty array on error.
     */
    public function do_get($url, $port=80, $headers=NULL)
    {
      $retarr = array();  // Return value

      $curl_opts = array(CURLOPT_URL => $url,
                         CURLOPT_PORT => $port,
                         CURLOPT_POST => false,
                         CURLOPT_SSL_VERIFYHOST => false,
                         CURLOPT_SSL_VERIFYPEER => false,
                         CURLOPT_RETURNTRANSFER => true);

      if ($headers) { $curl_opts[CURLOPT_HTTPHEADER] = $headers; }

      $response = $this->do_curl($curl_opts);

      if (! empty($response)) { $retarr = $response; }

      return $retarr;
    }

    /**
     * Do an HTTP POST
     * @param string $url
     * @param int $port (optional)
     * @param array $headers an array of HTTP headers (optional)
     * @return array ($info, $header, $response) on success or empty array on error.
     */
    public function do_post($url, $postbody, $port=80, $headers=NULL)
    {
      $retarr = array();  // Return value

      $curl_opts = array(CURLOPT_URL => $url,
                         CURLOPT_PORT => $port,
                         CURLOPT_POST => true,
                         CURLOPT_SSL_VERIFYHOST => false,
                         CURLOPT_SSL_VERIFYPEER => false,
                         CURLOPT_POSTFIELDS => $postbody,
                         CURLOPT_RETURNTRANSFER => true);

      if ($headers) { $curl_opts[CURLOPT_HTTPHEADER] = $headers; }

      $response = $this->do_curl($curl_opts);

      if (! empty($response)) { $retarr = $response; }

      return $retarr;
    }

    /**
     * Make a curl call with given options.
     * @param array $curl_opts an array of options to curl
     * @return array ($info, $header, $response) on success or empty array on error.
     */
    public function do_curl($curl_opts)
    {
      $retarr = array();  // Return value
      if (! $curl_opts) {
        return $retarr;
      }


      // Open curl session
      $ch = curl_init();

      if (! $ch) {
        return $retarr;
      }
      curl_setopt_array($ch, $curl_opts);
      curl_setopt($ch, CURLOPT_HEADER, true);
      ob_start();
      $response = curl_exec($ch);
      $curl_spew = ob_get_contents();
      ob_end_clean();
      if (curl_errno($ch)) {
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        unset($ch);
        return $retarr;
      }


      // Get information about the transfer
      $info = curl_getinfo($ch);

      // Parse out header and body
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $header = substr($response, 0, $header_size);
      $body = substr($response, $header_size );

      // Close curl session
      curl_close($ch);
      unset($ch);
      // Set return value
      array_push($retarr, $info, $header, $body);

      return $retarr;
    }

    /**
     * Pretty print some JSON
     * @param string $json The packed JSON as a string
     * @param bool $html_output true if the output should be escaped
     * (for use in HTML)
     * @link http://us2.php.net/manual/en/function.json-encode.php#80339
     */
    public function json_pretty_print($json, $html_output=false)
    {
        $spacer = '  ';
        $level = 1;
        $indent = 0; // current indentation level
        $pretty_json = '';
        $in_string = false;
        $len = strlen($json);
        for ($c = 0; $c < $len; $c++) {
            $char = $json[$c];
            switch ($char) {
                case '{':
                case '[':
                    if (!$in_string) {
                        $indent += $level;
                        $pretty_json .= $char . "\n" . str_repeat($spacer, $indent);
                    } else {
                        $pretty_json .= $char;
                    }
                break;
                case '}':
                case ']':
                    if (!$in_string) {
                        $indent -= $level;
                        $pretty_json .= "\n" . str_repeat($spacer, $indent) . $char;
                    } else {
                        $pretty_json .= $char;
                    }
                break;
                case ',':
                    if (!$in_string) {
                        $pretty_json .= ",\n" . str_repeat($spacer, $indent);
                    } else {
                        $pretty_json .= $char;
                    }
                break;
                case ':':
                    if (!$in_string) {
                        $pretty_json .= ": ";
                    } else {
                        $pretty_json .= $char;
                    }
                break;
                case '"':
                    if ($c > 0 && $json[$c-1] != '\\') {
                        $in_string = !$in_string;
                    }
                default:
                    $pretty_json .= $char;
                break;
            }
        }

        return ($html_output) ?
        '<pre>' . htmlentities($pretty_json) . '</pre>' :
        $pretty_json . "\n";
    }

    public function callcontact_yahoo($consumer_key, $consumer_secret, $guid, $access_token, $access_token_secret, $usePost=false, $passOAuthInHeader=true)
    {
        $retarr = array();  // return value
        $response = array();

        $url = 'https://social.yahooapis.com/v1/user/' . $guid . '/contacts;count=1000';
        $params['format'] = 'json';
        $params['view'] = 'compact';
        $params['oauth_version'] = '1.0';
        $params['oauth_nonce'] = mt_rand();
        $params['oauth_timestamp'] = time();
        $params['oauth_consumer_key'] = $consumer_key;
        $params['oauth_token'] = $access_token;

        // compute hmac-sha1 signature and add it to the params list
        $params['oauth_signature_method'] = 'HMAC-SHA1';
        $params['oauth_signature'] =
            $this->oauth_compute_hmac_sig($usePost? 'POST' : 'GET', $url, $params,
        $consumer_secret, $access_token_secret);

        // Pass OAuth credentials in a separate header or in the query string
        if ($passOAuthInHeader) {
            $query_parameter_string = $this->oauth_http_build_query($params, true);
            $header = $this->build_oauth_header($params, "yahooapis.com");
            $headers[] = $header;
        } else {
            $query_parameter_string = $this->oauth_http_build_query($params);
        }

        // POST or GET the request
        if ($usePost) {
            $request_url = $url;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $response = $this->do_post($request_url, $query_parameter_string, 443, $headers);
        } else {
                $request_url = $url . ($query_parameter_string ?
                ('?' . $query_parameter_string) : '' );
                $response = $this->do_get($request_url, 443, $headers);
        }
        $yahoo_array = array();
        /*$newList	= "<table>
        <tr><td>Name </td><td>Email </td><td><a href='javascript:;' class='select_bt'>Select All</a>/<a href='javascript:;' class='clear_bt'>Clear All</a></td>
        </tr>";*/
        $newList = "";
        // extract successful response
        return json_decode($response[2], true);
        if (! empty($response)) {
            list($info, $header, $body) = $response;
            if ($body) {
            $yahoo_array = json_decode($body);

            echo "<pre/>";
            print_r($yahoo_array);
            foreach($yahoo_array as $key=>$values){
                foreach($values->contact as $keys=>$values_sub){
                    $email = $values_sub->fields[1]->value;
                    if(trim($email)!="")
                    $newList   .= $email.",";
                }
            }

            }
            $retarr = $newList."";
        }

      return $retarr;
    }
    public function get_access_token_yahoo($consumer_key, $consumer_secret, $request_token, $request_token_secret, $oauth_verifier, $usePost=false, $useHmacSha1Sig=true, $passOAuthInHeader=true)
    {
        $retarr = array();  // return value
        $response = array();
        $url = 'https://api.login.yahoo.com/oauth/v2/get_token';
        $params['oauth_version'] = '1.0';
        $params['oauth_nonce'] = mt_rand();
        $params['oauth_timestamp'] = time();
        $params['oauth_consumer_key'] = $consumer_key;
        $params['oauth_token']= $request_token;
        $params['oauth_verifier'] = $oauth_verifier;

        // compute signature and add it to the params list
        if ($useHmacSha1Sig) {
            $params['oauth_signature_method'] = 'HMAC-SHA1';
            $params['oauth_signature'] = $this->oauth_compute_hmac_sig($usePost? 'POST' : 'GET', $url, $params,
            $consumer_secret, $request_token_secret);
        } else {
            $params['oauth_signature_method'] = 'PLAINTEXT';
            $params['oauth_signature'] = $this->oauth_compute_plaintext_sig($consumer_secret, $request_token_secret);
        }

        // Pass OAuth credentials in a separate header or in the query string
        if ($passOAuthInHeader) {
            $query_parameter_string = $this->oauth_http_build_query($params, false);
            $header = $this->build_oauth_header($params, "yahooapis.com");
            $headers[] = $header;
        } else {
            $query_parameter_string = $this->oauth_http_build_query($params);
        }
        // POST or GET the request
        if ($usePost) {
            $request_url = $url;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $response = $this->do_post($request_url, $query_parameter_string, 443, $headers);
        } else {
            $request_url = $url . ($query_parameter_string ?
                            ('?' . $query_parameter_string) : '' );
            $response = $this->do_get($request_url, 443, $headers);
        }
        // extract successful response
        if (! empty($response)) {
            list($info, $header, $body) = $response;
            $body_parsed = $this->oauth_parse_str($body);
            $retarr = $response;
            $retarr[] = $body_parsed;
        }

        return $retarr;
    }

    /* Function added for getting the request token */
    public function get_request_token($consumer_key, $consumer_secret, $callback, $usePost=false, $useHmacSha1Sig=true, $passOAuthInHeader=false)
    {
        $retarr = array();  // return value
        $response = array();

        $url = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
        $params['oauth_version'] = '1.0';
        $params['oauth_nonce'] = mt_rand();
        $params['oauth_timestamp'] = time();
        $params['oauth_consumer_key'] = $consumer_key;
        $params['oauth_callback'] = $callback;

        // compute signature and add it to the params list
        if ($useHmacSha1Sig) {
            $params['oauth_signature_method'] = 'HMAC-SHA1';
            $params['oauth_signature'] = $this->oauth_compute_hmac_sig($usePost? 'POST' : 'GET', $url, $params,
            $consumer_secret, null);
        } else {
            $params['oauth_signature_method'] = 'PLAINTEXT';
            $params['oauth_signature'] = $this->oauth_compute_plaintext_sig($consumer_secret, null);
        }

        // Pass OAuth credentials in a separate header or in the query string
        if ($passOAuthInHeader) {
            $query_parameter_string = $this->oauth_http_build_query($params, FALSE);
            $header = $this->build_oauth_header($params, "yahooapis.com");
            $headers[] = $header;
        } else {
            $query_parameter_string = $this->oauth_http_build_query($params);
        }

        // POST or GET the request
        if ($usePost) {
            $request_url = $url;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $response = $this->do_post($request_url, $query_parameter_string, 443, $headers);
        } else {
            $request_url = $url . ($query_parameter_string ?
                    ('?' . $query_parameter_string) : '' );
            $response = $this->do_get($request_url, 443, $headers);

        }
        // extract successful response
        if (! empty($response)) {
            list($info, $header, $body) = $response;
            $body_parsed = $this->oauth_parse_str($body);
            $retarr = $response;
            $retarr[] = $body_parsed;
        }

        return $retarr;
    }

    public function getContacts($token, $secret, $verif)
    {
        $ret = $this->get_access_token_yahoo($this->consumer_key, $this->consumer_secret, $token, $secret, $verif, false, true, true);
        $guid = $ret[3]['xoauth_yahoo_guid'];
        $access_token_secret = $ret[3]['oauth_token_secret'];
        $access_token = $this->rfc3986_decode($ret[3]['oauth_token']);
        $contacts = $this->callcontact_yahoo($this->consumer_key, $this->consumer_secret,
            $guid, $access_token, $access_token_secret, false, true);
        return $contacts;
    }
}

?>
