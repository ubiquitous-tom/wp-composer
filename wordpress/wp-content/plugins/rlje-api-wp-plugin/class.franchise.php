<?php

/**
 * Franchise API
 *
 * @author gruiz
 */
class Franchise extends Resource {
    CONST URL_FRANCHISE_API_PATH = '/today/web/franchise/';
    //
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
     * Get Franchise by Id
     * @param String $franchiseId
     * @return Array Object
     */
    public function getFranchiseById($franchiseId){
        $serviceUrl = self::URL_FRANCHISE_API_PATH. $franchiseId .'.json';
        $filterByUrl = $this->replaceDefaultURLParams($serviceUrl);
        return $this->doGetApi($filterByUrl);
    }
}
