<?php

/**
 * Related class
 *
 */
class Related extends Resource {
    CONST URL_VIEWERS_ALSO_WATCHED_API_PATH = 'https://activity-api.rlje.net/recommendationsengine/seealso?franchise_id=%s';
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
     * Get Franchises related according the viewers also watched criteria.
     * @param String $franchiseId
     * @return Array Object | Null
     */
    public function getViewersAlsoWatched($franchiseId){
        $viewersAlsoWatchedUrl = sprintf(self::URL_VIEWERS_ALSO_WATCHED_API_PATH, $franchiseId);
        if(strpos(CONTENT_BASE_URL, 'dev-')) {
            $viewersAlsoWatchedUrl = str_replace('activity-api.rlje.net', 'dev-activity-api.rlje.net', $viewersAlsoWatchedUrl);
        }
        return $this->doGetApi($viewersAlsoWatchedUrl, 2);
    }
}
