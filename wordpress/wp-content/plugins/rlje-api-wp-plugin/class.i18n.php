<?php

class I18N extends Resource
{
    CONST URL_RLJE_APP = 'https://app.rlje.net';
    CONST URL_LANG_API_PATH = '/i18n/%s/lang.json';

    private static $instance = null;
    
    /**
     * Call this method to get singleton
     *
     * @return I18N
     */
    public static function getInstance()
    {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
    }
    /**
     * Private constructor so nobody else can instance it
     *
     */
    private function __construct() { }

    /**
     * Get API data
     * @param String $type
     * @return Array Object
     */
    public function getData($type = 'native')
    {
        $serviceUrl = sprintf(self::URL_RLJE_APP . self::URL_LANG_API_PATH, $type);

        return $this->doGetApi($serviceUrl, 2);
    }

    /**
     * Get Available locales
     * @param String $type
     * @return Array Object
     */
    public function getLocale($type = 'native')
    {
        $data = $this->getData($type);
        if (!empty($data[0]) && isset($data[0]->languages)) {
            return $data[0]->languages;
        }

        return array();
    }

    /**
     * Get Available translations
     * @param String $type
     * @return Array Object
     */
    public function getTranslations($type = 'native')
    {
        $data = $this->getData($type);
        if (!empty($data[0]) && isset($data[0]->tr)) {
            return $data[0]->tr;
        }

        return array();
    }
}