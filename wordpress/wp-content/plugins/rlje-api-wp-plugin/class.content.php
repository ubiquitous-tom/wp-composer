<?php

class Content extends Resource {
    CONST URL_BROWSE_API_PATH = '/today/web/content/';
    CONST URL_HOME_API = '/today/web/home.json';
    CONST URL_BROWSE_API = '/today/web/guide.json';
    CONST URL_CONTENT_PAGE_API = '/today/web/contentpage/%s/%s.json';

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
     * Get Browse Items
     * @param String $collectionOrGenre
     * @return Array Object
     */
    public function getCollectionOrGenre($collectionOrGenre){
        $serviceUrl = self::URL_BROWSE_API_PATH. $collectionOrGenre .'.json';
        $filterByUrl = $this->replaceDefaultURLParams($serviceUrl);
        return $this->doGetApi($filterByUrl);
    }

    /**
     * Get Home Items
     * @return Array Object | null
     */
    public function getHomeItems(){
        $result = $this->doGetApi($this->replaceDefaultURLParams(self::URL_HOME_API));
        return $result;
    }

    /**
     * Get Browse Items
     * @return Array Object | null
     */
    public function getBrowseItems(){
        $result = $this->doGetApi($this->replaceDefaultURLParams(self::URL_BROWSE_API));
        return $result;
    }

    /**
     * Get Browse All Items
     * @return Array Object | null
     */
    public function getBrowseAllItems(){
        $result = $this->doGetApi($this->replaceDefaultURLParams(self::URL_BROWSE_API . '?content=mediaonly&orderBy=date'));
        return $result;
    }

    /**
     * Get Content by Page
     * @return Array Object | null
     */
    public function getContentPageItems($content=null, $page=null){
        $result = null;
        if(!empty($content) && !empty($page)) {
            $API_URL = sprintf(self::URL_CONTENT_PAGE_API, $page, $content);
            $result = $this->doGetApi($this->replaceDefaultURLParams($API_URL));
        }

        return $result;
    }
}
