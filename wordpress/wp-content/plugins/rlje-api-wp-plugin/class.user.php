<?php

/*
 * User account.
 */

class User extends Resource {
    CONST URL_ACORN_USER_RECENTLY_WATHCED_API_PATH = '/preference/preferencesinitial?SessionID=%s&Category=recentlyWatched';
    CONST URL_ACORN_USER_WATCHLIST_API_PATH = '/preference/preferencesinitial?SessionID=%s&Category=watchlist';
    CONST URL_ACORN_USER_PROFILE_API_PATH = '/profile?SessionID=%s';
    CONST URL_ACORN_USER_PREFERENCE_WATCHLIST_API_PATH = '/preference/watchlist';
    CONST URL_ACORN_USER_PREFERENCE_WATCHLIST_PARAM_API_PATH = '/preference/watchlist?SessionID=%s&FranchiseID=%s';
    CONST URL_ACORN_USER_PREFERENCE_STREAMPOSITION_API_PATH = '/preference/streamposition';
    CONST URL_ACORN_USER_PREFERENCE_STREAMPOSITION_PARAM_API_PATH = '/preference/streamposition?SessionID=%s&FranchiseID=%s';
    CONST URL_ACORN_USER_PREFERENCE_ENTITLEMENT_PARAM_API_PATH = '/preference/streamauthentitled?SessionID=%s';
    CONST URL_ACORN_CONTACT_US_API_PATH = 'http://activity-api.rlje.net/problem2?SessionID=%s&CookiesEnabled=%s&Browser=%s&ScreenSize=%s&ReferringURL=%s&FlashPlayer=%s&Description=%s&UserAgent=%s&Title=%s&Model=%s&ConnectionSpeed=%s&Email=%s';
    CONST URL_ACORN_OPTIN_API_PATH = '/optIn?Email=%s';
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
     * Get Recently Watched by user SessionId
     * @param String $sessionId
     * @return Array Object
     */
    public function getUserRecentlyWatched($sessionId) {
        $userRecentlyWatched = array();
        $userRecentlyWatchedUrl = $this->addCountryCodeParam(sprintf(self::URL_ACORN_USER_RECENTLY_WATHCED_API_PATH, $sessionId));
        $response = $this->doGetApi($userRecentlyWatchedUrl, 1);
        if(isset($response->franchises)) {
            $userRecentlyWatched = $response->franchises;
        }
        return $userRecentlyWatched;
    }
    
    /**
     * Get Watchlist by user SessionId
     * @param String $sessionId
     * @return Array Object
     */
    public function getUserWatchlist($sessionId) {
        $userWatchlist = array();
        $userWatchlistUrl = $this->addCountryCodeParam(sprintf(self::URL_ACORN_USER_WATCHLIST_API_PATH, $sessionId));
        $response = $this->doGetApi($userWatchlistUrl, 1);
        if(isset($response->franchises)) {
            $userWatchlist = $response->franchises;
        }
        return $userWatchlist;
    }
    
    /**
     * Get User Profile by SessionId
     * @param String $sessionId
     * @return Array Object | null
     */
    public function getUserProfile($sessionId) {
        $userProfileUrl = sprintf(self::URL_ACORN_USER_PROFILE_API_PATH, $sessionId);
        return $this->doGetApi($userProfileUrl, 1);
    }

    /**
     * Add a franchise to watchlist by SessionId
     * @param String $franchiseId
     * @param String $sessionId
     * @return Array Object | null
     */
    public function addToWatchlist($franchiseId, $sessionId) {
        $data = array(
            "SessionID" => $sessionId,
            "FranchiseID" => $franchiseId
        );
        return $this->doPostApi(self::URL_ACORN_USER_PREFERENCE_WATCHLIST_API_PATH, json_encode($data), 1);
    }
    
    /**
     * Remove a franchise from the watchlist by SessionId
     * @param String $franchiseId
     * @param String $sessionId
     * @return Array Object | null
     */
    public function removeFromWatchlist($franchiseId, $sessionId) {
        $userRemoveWatchlistUrl = sprintf(self::URL_ACORN_USER_PREFERENCE_WATCHLIST_PARAM_API_PATH, $sessionId, $franchiseId);
        return $this->doDeleteApi($userRemoveWatchlistUrl, null, 1);
    }
    
    /**
     * Get the stream positions about a franchise watched by SessionId
     * @param String $franchiseId
     * @param String $sessionId
     * @return Array Object | null
     */
    public function getStreamPositionsByFranchise($franchiseId, $sessionId) {
        $streamPositionsByFranchiseUrl = sprintf(self::URL_ACORN_USER_PREFERENCE_STREAMPOSITION_PARAM_API_PATH, $sessionId, $franchiseId);
        return $this->doGetApi($streamPositionsByFranchiseUrl, 1);
    }
    
    /**
     * Add a stream position about an episode watched by SessionId
     * @param String $episodeId
     * @param String $sessionId
     * @param String $position
     * @param String $lastKnownAction
     * @return Array Object | null
     */
    public function addStreamPosition($episodeId, $sessionId, $position, $lastKnownAction) {
        $data = array(
            "SessionID" => $sessionId,
            "StreamType" => 'PREMIUM',
            "Position" => intval($position),
            "EpisodeID" => $episodeId,
            "LastKnownAction" => $lastKnownAction
        );
        return $this->doPostApi(self::URL_ACORN_USER_PREFERENCE_STREAMPOSITION_API_PATH, json_encode($data), 1);
    }
    
    /**
     * Send contact form data
     * @param Array $formData
     * @return Array Object | null
     */
    public function sendContactFormData($formData) {
        $contactApiRestUrl = sprintf(
            self::URL_ACORN_CONTACT_US_API_PATH,
            (isset($formData['SessionID'])) ? urlencode(sanitize_text_field($formData['SessionID'])) : '',
            (isset($formData['CookiesEnabled'])) ? urlencode(sanitize_text_field($formData['CookiesEnabled'])) : '',
            (isset($formData['Browser'])) ? urlencode(sanitize_text_field($formData['Browser'])) : '',
            (isset($formData['ScreenSize'])) ? urlencode(sanitize_text_field($formData['ScreenSize'])) : '',
            (isset($formData['ReferringURL'])) ? urlencode(sanitize_text_field($formData['ReferringURL'])) : '',
            (isset($formData['FlashPlayer'])) ? urlencode(sanitize_text_field($formData['FlashPlayer'])) : '',
            (isset($formData['Description'])) ? urlencode(urldecode($formData['Description'])) : '',
            (isset($formData['UserAgent'])) ? urlencode(sanitize_text_field($formData['UserAgent'])) : '',
            (isset($formData['Title'])) ? urlencode(sanitize_text_field($formData['Title'])) : '',
            (isset($formData['Model'])) ? urlencode(sanitize_text_field($formData['Model'])) : '',
            (isset($formData['ConnectionSpeed'])) ? urlencode(sanitize_text_field($formData['ConnectionSpeed'])) : '',
            (isset($formData['Email'])) ? urlencode(sanitize_email($formData['Email'])) : ''
        );

        if(strpos(CONTENT_BASE_URL, 'dev-')) {
            $contactApiRestUrl = str_replace('activity-api.rlje.net', 'dev-activity-api.rlje.net', $contactApiRestUrl);
        }

        return $this->doGetApi($contactApiRestUrl, 2);
    }

    /**
     * Send the email address to signup the newsletter.
     * @param string $email Email address to subscribe.
     * @return Array Object | null
     */
    public function signupNewsletter($email) {
        $signupNewsletterApiRestUrl = sprintf(
            self::URL_ACORN_OPTIN_API_PATH,
            urlencode(sanitize_email($email))
        );
        return $this->doGetApi($signupNewsletterApiRestUrl, 1);
    }

    /**
     * Checks if a franchise exist in the user's watchlist by sessionId. 
     * @param String $franchiseId
     * @param String $sessionId
     * @return Boolean
     */
    public function isFranchiseAddedToWatchlist($franchiseId, $sessionId) {
        $return = false;
        
        if(isset($franchiseId, $sessionId)) {
            $wathchlists = self::getUserWatchlist($sessionId);
            foreach($wathchlists as $watchlist) {
                if(isset($watchlist->id) && $watchlist->id === $franchiseId) {
                    $return = true;
                    break;
                }
            }
        }
        return $return;
    }
    
    /**
     * Add country code param to URL.
     * @param string $url URL to add the Country Code parameter.
     * @return string URL with or without country code parameter.
     */
    public function addCountryCodeParam($url) {
        $countryCode = $this->getCountryCode();
        if(!empty($countryCode)) {
            $url .= '&country='.$countryCode;
        }
        return $url;
    }

    public function concurrentStreams($sessionId) {
        $userProfileUrl = sprintf(self::URL_ACORN_USER_PREFERENCE_ENTITLEMENT_PARAM_API_PATH, $sessionId);
        return $this->doGetApi($userProfileUrl, 1);
    }
    
}
