<?php 

/*
 * Search Franchises.
 */

class Search extends Resource {
    CONST UR_SEARCH_API_PATH  = 'https://api.rlje.net/cms/admin/find?property=%s&q=%s';

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
     * Search by Franchises
     * @param String $search
     * @return Array Object | null
     */
    public function getByFranchises($search) {
        $URL = $this->getURLByEnvironment(self::UR_SEARCH_API_PATH).$this->getCountryParam();
        $property = $this->getCurrentProperty();
        $filterByFranchiseUrl = sprintf($URL, $property, urlencode($search), 'activeFranchises');
        return $this->doGetApi($filterByFranchiseUrl, 2);
    }
    
    /**
     * Search franchises by person (actor, director)
     * @param String $search
     * @return Array Object | null
     */
    public function getFranchisesByPerson($search) {
        $URL = $this->getURLByEnvironment(self::UR_SEARCH_API_PATH.'&queryAttr=cast').$this->getCountryParam();
        $property = $this->getCurrentProperty();
        $filterByPersonUrl = sprintf($URL, $property, urlencode($search));
        return $this->doGetApi($filterByPersonUrl, 2);
    }
    
    /**
     * Search by Episodes
     * @param String $search
     * @return Array Object | null
     */
    public function getByEpisodes($search) {
        $URL = $this->getURLByEnvironment(self::UR_SEARCH_API_PATH).$this->getCountryParam();
        $property = $this->getCurrentProperty();
        $filterByEpisodesUrl = sprintf($URL, $property, urlencode($search));
        $episodes = $this->doGetApi($filterByEpisodesUrl, 2);
        if (isset($episodes->episodes)) {
            foreach ($episodes->episodes as $key=>$episode) {
                if(stripos($episode->type, 'episode') !== 0) {
                    unset($episodes->episodes[$key]);
                }
            }
            $episodes->episodes = array_values($episodes->episodes);
        }
        
        return $episodes;
    }
    
    /**
     * Get country parameter to add it to the api url.
     * @return string
     */
    private function getCountryParam() {
        $countryParam = '';
        $countryCode = $this->getCountryCode();
        if(!empty($countryCode)) {
            $countryParam = '&country='.$countryCode;
        }
        return $countryParam;
    }
}
