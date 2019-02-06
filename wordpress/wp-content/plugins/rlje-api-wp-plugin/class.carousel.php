<?php
class Carousel extends Resource {
    CONST URL_INITIAL_API_SEARCH = '/today/web/initial.json';
    CONST NAME_BLOCK_ADS = "ads";
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
    //
    /**
     * 
     * @return Array|null
     */
    public function getCarouselItems(){
        $result = $this->doGetApi($this->replaceDefaultURLParams(self::URL_INITIAL_API_SEARCH));
       
        return $result;
    }
    
    /**
     * Retrieve the adwards
     * 
     *  @return Array|null Description
     */
    public function getAds(){
        $result = $this->doGetApi($this->replaceDefaultURLParams(self::URL_INITIAL_API_SEARCH));
        if (is_string($result)){
            $result = json_decode($result);
            foreach ($result as $data) {
                if($data->id == self::NAME_BLOCK_ADS){
                    if (isset($data->options)){
                        foreach ($data->options as $op) {
                            $result[]= (isset($op->image)) ? $op->image : '';
                        }
                    } else {
                        $result  = array();
                    }
                }
            }
        } else {
            $result = null;
        }
        
        return $result;
    }
}
?>