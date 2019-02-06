<?php

class Resource {
    CONST XS_ATV_HASH = '81787e0f6fbf4119e04d70a08104d6e9';
    CONST RESPONSE_SERVICE_FORMAT = 'application/json';
    private static $instance = null;
    
    /**
     * Call this method to get singleton
     *
     * @return Contact
     */
    public static function getInstance(){
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
    }

    /**
     * Private constructor so nobody else can instance it
     *
     */
    private function __construct(){}

    /**
     *  This method make a call to API CMS
     * 
     * @param  String $path The path in the service
     * @param  String $envKey (Defaut value 0)  Environment key value to get the url base from getBaseUrl with these possible values:
     *                        $envKey = 0 set the Content Base URL.
     *                        $envKey = 1 set the RLJE Base URL.
     *                        $envKey = 2 set YOUR Base URL sending the full url in the $path param.
     * 
     * @return Array|null   Retrieve the results        
     */
    public function doGetApi($path, $envKey = 0, $cookie = null) {
        $url = $this->getBaseUrl($envKey) . $path;
        $result = null;
        $headers = array(
            'Content-Type' => self::RESPONSE_SERVICE_FORMAT
        );
        if(!empty($cookie)) {
            $headers['Cookie'] = $cookie;
        }
        
        // Get API timeout in seconds.
        $timeout = $this->getAPITimeout();
        
        $response = wp_remote_get($url, array(
            'timeout' => $timeout,
            'headers' => $headers
        ));

        if (!is_wp_error( $response ) && (isset($response['body']) && is_string($response['body']))) {
            $response = ($response['response']['code'] == 200) ? json_decode($response['body']) : $response['response'];
            if(!isset($response->error)) {
                $result = $response;
            }
        }
        else {
            error_log($url.': '.print_r($response, true));
        }

        return $result;
    }
    
    /** Get API timeout in Seconds
     * 
     * @return int Timeout time in seconds.
     */
    private function getAPITimeout() {
        // Only for ATV sets 2 seconds in the timeout otherwise sets 60 seconds 
        // by default if it is not set API_TIMEOUT_SECS environment variable.
        $isATV = (preg_match("/acorn/", RLJE_BASE_URL));
        $defaultTimeout = ($isATV) ? 2 : 60;
        $timeout = (defined('API_TIMEOUT_SECS') && !empty(API_TIMEOUT_SECS)) ? API_TIMEOUT_SECS : $defaultTimeout;
        return intval($timeout);
    }
    
    /**
     *  This method make a post call to API CMS
     * 
     * @param  String $path The path in the service
     * @param  Object $body The body content.
     * @param  String $envKey (Defaut value 0)  Environment key value to get the url base from getBaseUrl with these possible values:
     *                        $envKey = 0 set the Content Base URL.
     *                        $envKey = 1 set the RLJE Base URL.
     *                        $envKey = 2 set YOUR Base URL sending the full url in the $path param.
     * 
     * @return Array|null   Retrieve the results        
     */
    public function doPostApi($path, $body, $envKey = 0) {
        $url = $this->getBaseUrl($envKey) . $path;
        $result = null;
        $response = wp_remote_post($url, array(
            'headers' => array(
                'xs-atv-hash' => self::XS_ATV_HASH,
                'Content-Type' => self::RESPONSE_SERVICE_FORMAT
            ),
            'body' => $body
        ));

        if (!is_wp_error( $response ) && (isset($response['body']) && is_string($response['body']))) {
            $response = json_decode($response['body']);
            if(!isset($response->error)) {
                $result = $response;
            }
        }

        return $result;
    }
    
    /**
     *  This method make a delete call to API CMS
     * 
     * @param  String $path The path in the service
     * @param  Json   $body The body content.
     * @param  String $envKey (Defaut value 0)  Environment key value to get the url base from getBaseUrl with these possible values:
     *                        $envKey = 0 set the Content Base URL.
     *                        $envKey = 1 set the RLJE Base URL.
     *                        $envKey = 2 set YOUR Base URL sending the full url in the $path param.
     * 
     * @return Array|null   Retrieve the results        
     */
    public function doDeleteApi($path, $body=null, $envKey = 0) {
        $url = $this->getBaseUrl($envKey) . $path;
        $result = null;
        $args = array(
            'method' => 'DELETE',
            'headers' => array(
                'xs-atv-hash' => self::XS_ATV_HASH,
                'Content-Type' => self::RESPONSE_SERVICE_FORMAT
            )
        );
        if (isset($body)) {
            $args['body'] = $body;
        }
        $response = wp_remote_request($url, $args);
        if (!is_wp_error( $response ) && (isset($response['response'], $response['response']['code']))) {
            if($response['response']['code'] == 200) {
                $result = 'OK';
            }
            else {
                $result = $response;
            }
        }
        return $result;
    }

    /**
     * Retrieve the url base to the service from an environment mapper with these possible values:
     * 
     * @param  String $key (Defaut value 0) Key value to get the environment url base with these possible values:
     *                      $key = 0 set the Content Base URL.
     *                      $key = 1 set the RLJE Base URL.
     *                      $key = 2 set an empty value in order to set YOUR Base URL.
     * @return String   Contain the base url api.
     * 
     */
    private function getBaseUrl($key) {
        $envMapper = array(
            CONTENT_BASE_URL,
            RLJE_BASE_URL,
            ''
        );
        return $envMapper[$key];
    }
    
    /**
     * Get the current property to use in the API calls.
     * @return String   Contain the properto to use in the api.
     */
    public function getCurrentProperty() {
        $property = '';
        preg_match('/http[s]*\:\/\/.+\/(.+)/', RLJE_BASE_URL, $getProperty);
        if(isset($getProperty[1])) {
            $property = $getProperty[1];
        }
        return $property;
    }
    
    /**
     * Get the proper url by environment variable.
     * @param String   $prodUrl      Production url where adds the environment prefix.
     * @param Interger $constantKey  Use 0 value to check environment by CONTENT_BASE_URL (default) or 1 to RLJE_BASE_URL.
     * @param String   $separator    By default uses a dash after add the environment prefix in the url (dev- or qa-).
     * @return String
     */
    public function getURLByEnvironment($prodUrl, $constantKey=0, $separator = '-') {
        $constants = array(
            'CONTENT_BASE_URL',
            'RLJE_BASE_URL'
        );
        $envUrl = $prodUrl;
        preg_match('/http[s]*\:\/\/(.+)-api.rlje.net\/.+/', constant($constants[$constantKey]), $envPrefix);
        if(isset($envPrefix[1])) {
            $envUrl = str_replace('//', '//'.$envPrefix[1].$separator, $prodUrl);
        }
        return $envUrl;
    }
    
    /**
     * Sets or removes the future date in Cookie variable depending the date 
     * string param. If the param is 'today' or empty removes the cookie 
     * but if it is a valid date set the cookie.
     * @param Date String   $date   Date to set the Cookie variable in format Ymd.
     * @return boolean
     */
    public function setFutureDate($date) {
        $result = false;
        $isValidDate = (strtotime($date)) ? true : false;
        $time = 60*60*24*3; //Set time to 3 day.
        if($isValidDate && (!empty($date) && $date != 'today')) {
            setcookie('wordpress_futureDate', base64_encode(site_url().'@@'.$date), time() + $time, COOKIEPATH, COOKIE_DOMAIN);
            $result = true;
        }
        elseif(isset($_COOKIE['wordpress_futureDate'])) {
            setcookie('wordpress_futureDate', '', time() - $time, COOKIEPATH, COOKIE_DOMAIN);
        }
        return $result;
    }
    
    /**
     * Get the future date from Cookie variable.
     * @return string
     */
    public function getFutureDate() {
        $date = 'today';
        if(!empty($_COOKIE['wordpress_futureDate'])) {
            $getFutureDate = explode('@@', base64_decode($_COOKIE['wordpress_futureDate']));
            if($getFutureDate[0] === site_url()) {
                $date = $getFutureDate[1];
            }
        }
        return $date;
    }
    
    /**
     * Sets or removes the country to filter in Cookies variable depending the 
     * string param. If the param is 'clear' or empty removes the cookie 
     * but if is a valid code set the cookie.
     * @param Date String   $code   Country code to set the cookie in format Ymd.
     * @return boolean
     */
    public function setCountryToFilter($code) {
        $result = false;
        $isValidCode = $this->isCountryCodeValid($code);
        $time = 60*60*24*3; //Set time to 3 day.
        if($isValidCode && (!empty($code) && $code != 'clear')) {
            setcookie('wordpress_countryFilter', base64_encode(site_url().'@@'.$code), time() + $time, COOKIEPATH, COOKIE_DOMAIN);
            $result = true;
        }
        elseif(isset($_COOKIE['wordpress_countryFilter'])) {
            setcookie('wordpress_countryFilter', '', time() - $time, COOKIEPATH, COOKIE_DOMAIN);
        }
        return $result;
    }
    
    /**
     * Checks if the country code is valid.
     * @param string $code Country code to check if it is valid.
     * @return boolean
     */
    private function isCountryCodeValid($code) {
        $isValid = false;
        if(!empty($code)) {
            $path = '/today/web/initial.json?country='.$code;
            $checkCode = $this->doGetApi($path);
            if(count($checkCode) > 1 || (isset($checkCode['code']) && $checkCode['code'] == 204)) {
                $isValid = true;
            }
            else if(!empty($checkCode)) {
                error_log('Error Checking Country Code: '.print_R($checkCode, true));
            }
        }
        return $isValid;
    }
    
    /**
     * Get the country to filter from Cookie variable.
     * @return string || null
     */
    public function getCountryToFilter() {
        $countryCode = null;
        if(!empty($_COOKIE['wordpress_countryFilter'])) {
            $getCountryCode = explode('@@', base64_decode($_COOKIE['wordpress_countryFilter']));
            if($getCountryCode[0] === site_url()) {
                $countryCode = $getCountryCode[1];
            }
        }
        return $countryCode;
    }
    
    /**
     * Replace the default params in the services URL is it get futuredate or 
     * country filter set in the site.
     * @param String   $url   URL string to replace or add parameters accord to futuredate or country filter values.
     * @return String
     */
    public function replaceDefaultURLParams($url) {
        $query_string = array();
        $futureDate = $this->getFutureDate();
        $countryCode = $this->getCountryCode();
        $locale = $this->getLocaleCode();
        // Add future date param if it is set.
        if($futureDate !== 'today') {
            $url = str_replace('today', $futureDate, $url);
        }
        // Add country filter param if it is set.
        if(!empty($countryCode)) {
            $query_string['country'] = $countryCode;
        }
        // Add locale filter param if it is set.
        if(!empty($locale)) {
            $query_string['lang'] = $locale;
        }
        if (!empty($query_string)) {
            $qs = http_build_query($query_string);
            $url = $url.'?'.$qs;
        }
        return $url;
    }
    
    /**
     * Get the country code from the SERVER or COOKIE.
     * @return String || null
     */
    public function getCountryCode() {
        $countryCode = null;
        if(!empty($_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY']) && $_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY'] != 'US') {
            $countryCode = strtolower($_SERVER['HTTP_CLOUDFRONT_VIEWER_COUNTRY']);
        }
        $getCountryCode = $this->getCountryToFilter(); //From Cookie
        if(!empty($getCountryCode)) { 
            $countryCode = $getCountryCode;
        }
        return $countryCode;
    }
    
    /**
     * Sets or removes Video Debugger Cookie depending on 
     * parameter's value. If the parameter is 'off' or empty removes the cookie 
     * but if is 'on' set the cookie.
     * @param string  $status  Status to set the cookie.
     * @return boolean
     */
    public function setVideoDebugger($status) {
        $result = false;
        $videoDegugger = strtolower(get_query_var('section'));
        $time = 60*60*24*3; //Set time to 3 day.
        if('on' === $videoDegugger) {
            setcookie('wordpress_videodebugger', base64_encode(site_url().'@@'.'on'), time() + $time, COOKIEPATH, COOKIE_DOMAIN);
            $result = true;
        }
        else {
            setcookie('wordpress_videodebugger', '', time() - $time, COOKIEPATH, COOKIE_DOMAIN);
        }
        return $result;
    }
    
    /**
     * Checks if Video Debugger is On from Cookie.
     * @return boolean
     */
    public function isVideoDebuggerOn() {
        $isVideoDebuggerOn = false;
        if(!empty($_COOKIE['wordpress_videodebugger'])) {
            $getVideoDebugger = explode('@@', base64_decode($_COOKIE['wordpress_videodebugger']));
            if($getVideoDebugger[0] === site_url()) {
                $isVideoDebuggerOn = true;
            }
        }
        return $isVideoDebuggerOn;
    }

    /**
     * Get the locale code from the SERVER or COOKIE.
     * We are using en_US UNIX style because that's how
     * WordPress load the PO and MO file.
     * Ex: fr_CA.po
     * @return String || null
     */
    public function getLocaleCode($locale = 'en_US') {
        // Run the tests on the COOKIE first.
        if ( ! empty( $_COOKIE['ATVLocale'] ) ) {
            return $_COOKIE['ATVLocale'];
        }

        // If not COOKIE then check `accept-language` header.
        $langs = array();
        if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            // Break up string into pieces (languages and q factors).
            preg_match_all( '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse );
    
            if ( count( $lang_parse[1] ) ) {
                // Create a list like "en" => 0.8.
                $langs = array_combine( $lang_parse[1], $lang_parse[4] );
    
                // Set default to 1 for any without q factor.
                foreach ( $langs as $language => $language_weight ) {
                    if ( '' === $language_weight ) {
                        $langs[ $language ] = 1;
                    }
                }
    
                // Sort list based on value.
                arsort( $langs, SORT_NUMERIC );
            }
        }

        // Look through sorted list and use first one that matches our languages.
        foreach ( $langs as $lang => $val ) {
            if ( 0 === strpos( $lang, 'en' ) ) {
                return 'en'; //'en_US';
            } elseif ( 0 === strpos( $lang, 'es' ) ) {
                return 'es'; // 'es_MX';
            } elseif ( 0 === strpos( $lang, 'fr' ) ) {
                return 'fr'; //'fr_CA';
            }
        }

        // Show default language.
        $localization = explode( '_', $locale );
        return $localization[0];
    }
}