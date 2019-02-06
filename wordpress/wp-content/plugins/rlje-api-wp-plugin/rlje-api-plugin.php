<?php
/*
Plugin Name: RLJE API PLUGIN
Plugin URI:
Description:
Author: Valtira
Author URI: valtira.net
License: MIT
Text Domain: RLJE API PLUGIN
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define( 'RLJE_API_PLUGIN_VERSION', '1.0.0' );
define( 'RLJE_API_PLUGIN__URL', plugin_dir_url( __FILE__ ) );
define( 'RLJE_API_PLUGIN__DIR', plugin_dir_path( __FILE__ ) );
define( 'RLJE_API_PLUGIN__TIME_REFRESH_CACHE', 900 ); //seconds

require_once ( RLJE_API_PLUGIN__DIR . 'class.resource.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.carousel.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.content.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.franchise.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.related.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.search.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.user.php');
require_once ( RLJE_API_PLUGIN__DIR . 'class.i18n.php');

/**
 * Retrieve an array that contain the ads image.
 *
 * @return Array
 */
function rljeApiWP_getAds(){
    $result = wp_cache_get('ads', 'homepage', true);
    if ( false === $result ) {
        $objCarousel = Carousel::getInstance();
        $result = $objCarousel->getAds();
        wp_cache_set('ads', $result, 'homepage', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $result;
}

/**
 * Get the full image url in accordance with the environment (prod, dev or qa).
 * @param string $image Image file name followed with some parameter for example "?h=150&w=300"
 * @return string
 */
function rljeApiWP_getImageUrlFromServices($image) {
    $baseImgUrl = RLJE_BASE_URL.'/artwork/size/'.$image;
    return $baseImgUrl;
}

/**
 * Retrieve an object that contains the initial.json items.
 * @param string $filterBy ID to filter in the initial.json
 * @return false || array
 */
function rljeApiWP_getInitialJSONItems($filterBy='all') {
    $key = 'initialJSON_'.$filterBy;
    $cacheKey = rljeApiWP_getCacheKey($key);
    $initialJSONItems = wp_cache_get($cacheKey, 'homepage');
    if ( false === $initialJSONItems ) {
        $objCarousel = Carousel::getInstance();
        $getInitialJSONItems = $objCarousel->getCarouselItems();
        if ($filterBy === 'all') {
            $initialJSONItems = $getInitialJSONItems;
        }
        else if(isset($getInitialJSONItems) && is_array($getInitialJSONItems)) {
            foreach ($getInitialJSONItems as $content) {
                if ($content->id === $filterBy) {
                    $initialJSONItems = $content;
                    break;
                }
            }
        }
        wp_cache_set($cacheKey, $initialJSONItems, 'homepage', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }
    return $initialJSONItems;
}

/**
 * Retrieve an array object that contains the initial.json items reducing the search of a franchise using the franchiseID as array key.
 * @param string $filterBy ID to filter in the initial.json
 * @return false || array
 */
function rljeApiWP_reducedInitialJSONItems($filterBy = 'all') {
    $key = 'reducedInitialJSON_'.$filterBy;
    $cacheKey = rljeApiWP_getCacheKey($key);
    $reducedItems = wp_cache_get($cacheKey, 'initialJson');
    if ( false === $reducedItems ) {
        $initialJSONItems = rljeApiWP_getInitialJSONItems($filterBy);
        if(false !== $initialJSONItems) {
            if(is_array($initialJSONItems)) {
                $reducedItems = array();
                foreach ($initialJSONItems as $key => $value) {
                    if(is_object($value)) {
                        $reducedItems[$key]= rljeApiWP_reduceInitObjKeys($value);
                    }
                }
            }
            else {
                $reducedItems = rljeApiWP_reduceInitObjKeys($initialJSONItems);
            }
            wp_cache_set($cacheKey, $reducedItems, 'initialJson', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }
    return $reducedItems;
}

/**
 * Method used to reduce the search of an item in a particular object in the initial.json using the franchiseID as array key.
 * @param string $obj Object in the initial.json
 * @return object
 */
function rljeApiWP_reduceInitObjKeys($obj) {
    $newObj = new stdClass();
    foreach ($obj as $key => $value) {
        if(is_array($value)) {
            $newObj->{$key} = array();
            foreach ($value as $nkey => $nvalue) {
                if(is_object($nvalue)) {
                    $id = (isset($nvalue->id)) ? $nvalue->id : $nvalue->franchiseID;
                    $newObj->{$key}[$id]= rljeApiWP_reduceInitObjKeys($nvalue);
                }
            }
        }
        else {
            $newObj->$key = $value;
        }
    }
    return $newObj;
}

/**
 * Retrieve an object that contains diffents carousel for home.
 *
 * @return stdClass
 */
function rljeApiWP_getCarouselItems() {
    $key = 'carousel';
    $cacheKey = rljeApiWP_getCacheKey($key);
    $carouselItems = wp_cache_get($cacheKey, 'homepage');
    if ( false === $carouselItems ) {
        $objCarousel = Carousel::getInstance();
        $getCarouselItems = $objCarousel->getCarouselItems();
        if(isset($getCarouselItems) && is_array($getCarouselItems)) {
            $newObjCarousel = new stdClass();
            foreach ($getCarouselItems as $content) {
                switch ($content->id){
                    case "carousel":
                        if(isset($content->media)) {
                            $newObjCarousel->topcarousel = rljeApiWP_reduceSubCarousel($content->media, 'image', true);
                        }
                        break;
                    case "exclusive":
                        if(isset($content->media)) {
                            $newObjCarousel->onlyacorntv = rljeApiWP_reduceSubCarousel($content->media, 'franchiseID', false);
                        }
                        break;
                    case "browse":
                        if(isset($content->options)) {
                            foreach ($content->options as $obOptions) {
                                if(isset($obOptions->id)) {
                                    $name = str_replace(' ', '', $obOptions->id);
                                    $name = strtolower($name);
                                    //
                                    if(isset($obOptions->media)) {
                                        $newObjCarousel->$name = rljeApiWP_reduceSubCarousel($obOptions->media, 'franchiseID', false);
                                    }
                                }
                            }
                        }
                        break;
                }
            }
            $carouselItems = $newObjCarousel;
            wp_cache_set($cacheKey, $newObjCarousel, 'homepage', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } else {
            $carouselItems = new stdClass();
        }
    }

    return $carouselItems;
}

/**
 * Returns the items in a reduced way using href and img in the resulted object.
 * @param arrayObject $data    Items to reduce.
 * @param string      $imgKey  Key name where it is the imagein the object.
 * @param boolean     $top     if it is false, it adds '_avatar' at the end of the image file name.
 * @return array
 */
function rljeApiWP_reduceSubCarousel($data, $imgKey, $top = false){
    $objCarousel = array();
    foreach ($data as $oMedia) {
        if(!empty($oMedia->$imgKey)) {
            $imageFile = $oMedia->$imgKey;
            if (!$top) {
                $imageFile = $imageFile. '_avatar';
            }
            $img = rljeApiWP_getImageUrlFromServices($imageFile);
            if (!isset($objCarousel[$oMedia->$imgKey])) {
                $objCarousel[$oMedia->$imgKey] = new stdClass();
            }
            $objCarousel[$oMedia->$imgKey]->href = $oMedia->franchiseID;
            $objCarousel[$oMedia->$imgKey]->img = $img;
        }
        else {
            error_log('Error: Doesn\'t exist image key "'.$imgKey.'" in this object: '.print_R($oMedia, true));
        }
    }

    return $objCarousel;
}

/**
 * Retrieve all the items by filter for browse page.
 * @param String $category Category to filter in the browse page.
 * @return array
 */
function rljeApiWP_getItemsByCategoryOrCollection($category) {
    $results = array();
    if(!empty($category)){
        $key = 'collection_'. $category;
        $cacheKey = rljeApiWP_getCacheKey($key);
        $results = wp_cache_get($cacheKey, 'collections');
        if (false === $results) {
            $content = Content::getInstance();
            $results = $content->getCollectionOrGenre($category);
            if (isset($results)) {
                wp_cache_set($cacheKey, $results, 'collections', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
            }
            else {
                $results = array();
            }
        }
    }

    return $results;
}

/**
 * Retrieve all the items browse all page.
 * @param String $cacheKey for the browse all page.
 * @return array
 */
function rljeApiWP_getBrowseAllItems($cacheKey) {
    $results = array();

    $results = wp_cache_get($cacheKey, 'browse');
    if (false === $results) {
        $content = Content::getInstance();
        $guide = $content->getBrowseAllItems();
        if (isset($guide[0]->media)) {
            $results = $guide[0]->media;
        }
        if (isset($results)) {
            wp_cache_set($cacheKey, $results, 'browse', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
        else {
            $results = array();
        }
    }

    return $results;
}

/**
 * Retrieve the comming soon items.
 *
 * @return array
 */
function rljeApiWP_getScheduleItems($category) {
    $key = $category.'_items';
    $cacheKey = rljeApiWP_getCacheKey($key);
    $scheduleItems = wp_cache_get($cacheKey, 'schedule', true);
    if (false === $scheduleItems) {
        $categoryItems = rljeApiWP_getItemsByCategoryOrCollection($category);
        $scheduleItems = array();
        if (isset($categoryItems) && is_array($categoryItems)){
            foreach ($categoryItems as $categoryItem) {
                if(isset($categoryItem->id)){
                    $searchFranchise = rljeApiWP_getFranchiseById($categoryItem->id);
                    if(isset($searchFranchise)){
                        if (isset($searchFranchise->episodes) && isset($searchFranchise->episodes[0])){
                            $categoryItem->trailerId = $searchFranchise->episodes[0]->id;
                        }
                        $scheduleItems[] = $categoryItem;
                    }
                }
            }
        }
        wp_cache_set($cacheKey, $scheduleItems, 'schedule', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $scheduleItems;
}

/**
 * Retrieve all the items by filter for browse page.
 * @param String $franchiseId Category to filter in the browse page.
 * @return array
 */
function rljeApiWP_getFranchiseById($franchiseId) {
    $key = 'franchise_'.md5($franchiseId);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $results = wp_cache_get($cacheKey, 'detail_', true);
    if (false === $results) {
        $franchise = Franchise::getInstance();
        $results = $franchise->getFranchiseById($franchiseId);
        if (isset($results)) {
            wp_cache_set($cacheKey, $results, 'detail_', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
        else {
            $results = array();
        }
    }

    return $results;
}

/**
 * Retrieve the season name converted to use in the URL.
 * @param String $seasonName Season name to convert to URL.
 * @return String
 */
function rljeApiWP_convertSeasonNameToURL ($seasonName) {
    $seasonURL = str_replace(
        array(' ',"'",'?',':','*','/','\\',',','.','^','(',')','#','$','%','&','!','|','¬','°','\"','=','<','>','+','~','`','@','¨'),
        '',
        strtolower($seasonName));
    return $seasonURL;
}

/**
 * Retrieve the episode name converted to URL Friendly.
 * @param String $episodeName Episode name to convert to URL Friendly.
 * @return String
 */
function rljeApiWP_convertEpisodeNameToURLFriendly ($episodeName) {
    $episodeURLFriendly = str_replace(
        array(' ', '?', "'",':','*','/','\\',',','.','^','(',')','#','$','%','&','!','|','¬','°','\"','=','<','>','+','~','`','@','¨'),
        array('-',''),
        strtolower($episodeName));
    return $episodeURLFriendly;
}

/**
 * Retrieve the season item filtered in a franchise array object.
 * @param String $franchiseId Franchise id to get data from the api service.
 * @param String $seasonName Season name to filter into the franchise array object.
 * @return array
 */
function rljeApiWP_getCurrentSeason($franchiseId, $seasonName) {
    $key = $franchiseId.'/'.$seasonName;
    $cacheKey = rljeApiWP_getCacheKey($key);
    $season = wp_cache_get($cacheKey, 'season', true);
    if (false === $season) {
        $franchise = rljeApiWP_getFranchiseById($franchiseId);
        $season = array();
        if (isset($franchise->seasons) && count($franchise->seasons) > 0) {
            foreach ($franchise->seasons as $key => $value) {
                if (rljeApiWP_convertSeasonNameToURL($value->name) === $seasonName) {
                    $season = $value;
                    $season->seasonNumber = $key+1;
                    $season->totalSeasons = count($franchise->seasons);
                    wp_cache_set($cacheKey, $season, 'season', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
                    break;
                }
            }
        }
    }

    return $season;
}

/**
 * Retrieve the episode item filtered by season in a franchise array object.
 * @param String $franchiseId Category to filter in the browse page.
 * @return array || null
 */
function rljeApiWP_getCurrentEpisode($franchiseId, $seasonName, $episodeName) {
    $key = $franchiseId.'/'.$seasonName.'/'.$episodeName;
    $cacheKey = rljeApiWP_getCacheKey($key);
    $episode = wp_cache_get($cacheKey, 'episode', true);
    if (false === $episode) {
        $season = rljeApiWP_getCurrentSeason($franchiseId, $seasonName);
        $episode = null;
        if (isset($season->episodes) && count($season->episodes) > 0) {
            foreach ($season->episodes as $key => $value) {
                if (rljeApiWP_convertEpisodeNameToURLFriendly($value->name) == $episodeName) {
                    $episode = $value;
                    $episode->episodeNumber = $key+1;
                    $episode->totalEpisodes = count($season->episodes);
                    wp_cache_set($cacheKey, $episode, 'episode', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
                    break;
                }
            }
        }
    }

    return $episode;
}

/**
 * Retrieve the previous episode url for the current episode in a season.
 * @param String $franchiseId Franchise id to get data from the api service.
 * @param String $seasonName Current season name to filter into the franchise array object.
 * @return array || null
 */
function rljeApiWP_getPreviousEpisodeURL($franchiseId, $seasonName, $episodeName) {
    $season = rljeApiWP_getCurrentSeason($franchiseId, $seasonName);
    $episode = rljeApiWP_getCurrentEpisode($franchiseId, $seasonName, $episodeName);
    $previousEpisodeURL = null;

    if (isset($episode->episodeNumber)) {
        if ($episode->episodeNumber > 1) {
            $previousEpisodeURL = '/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($seasonName).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($season->episodes[$episode->episodeNumber -2]->name);
        }
        else {
            $previousEpisodeURL = rljeApiWP_getLastEpisodeInPreviousSeason($franchiseId, $seasonName);
        }
    }
    return $previousEpisodeURL;
}

/**
 * Retrieve the url to the last episode in the previous season from the current season.
 * @param String $franchiseId Franchise id to get data from the api service.
 * @param String $seasonName Current season name to filter into the franchise array object.
 * @return array || null
 */
function rljeApiWP_getLastEpisodeInPreviousSeason($franchiseId, $seasonName) {
    $franchise = rljeApiWP_getFranchiseById($franchiseId);
    $lastEpisodeInPreviousSeasonURL = null;
    if (isset($franchise->seasons) && count($franchise->seasons) > 0) {
        foreach ($franchise->seasons as $key => $value) {
            $previousSeasonKey = $key-1;
            if (rljeApiWP_convertSeasonNameToURL($value->name) === $seasonName
                && $previousSeasonKey >= 0 && $previousSeasonKey < count($franchise->seasons)) {
                $lastEpisodeKey = count($franchise->seasons[$previousSeasonKey]->episodes)-1;
                $lastEpisode = isset($franchise->seasons[$previousSeasonKey]->episodes[$lastEpisodeKey]->name) ? $franchise->seasons[$previousSeasonKey]->episodes[$lastEpisodeKey]->name : '';
                $lastEpisodeInPreviousSeasonURL = '/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($franchise->seasons[$previousSeasonKey]->name).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($lastEpisode);
                break;
            }
        }
    }
    return $lastEpisodeInPreviousSeasonURL;
}

/**
 * Retrieve the next episode url for the current episode in a season.
 * @param String $franchiseId Franchise id to get data from the api service.
 * @param String $seasonName Current season name to filter into the franchise array object.
 * @return array || null
 */
function rljeApiWP_getNextEpisodeURL($franchiseId, $seasonName, $episodeName) {
    $season = rljeApiWP_getCurrentSeason($franchiseId, $seasonName);
    $episode = rljeApiWP_getCurrentEpisode($franchiseId, $seasonName, $episodeName);
    $nextEpisodeURL = null;

    if (isset($episode->episodeNumber)) {
        if ($episode->episodeNumber < $episode->totalEpisodes) {
            $nextEpisodeURL = '/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($seasonName).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($season->episodes[$episode->episodeNumber]->name);
        }
        else {
            $nextEpisodeURL = rljeApiWP_getFirstEpisodeInNextSeasonURL($franchiseId, $seasonName);
        }
    }
    return $nextEpisodeURL;
}

/**
 * Retrieve the url to the first episode in the next season from the current season.
 * @param String $franchiseId Franchise id to get data from the api service.
 * @param String $seasonName Current season name to filter into the franchise array object.
 * @return array || null
 */
function rljeApiWP_getFirstEpisodeInNextSeasonURL($franchiseId, $seasonName) {
    $franchise = rljeApiWP_getFranchiseById($franchiseId);
    $firstEpisodeInNextSeasonURL = null;
    if (isset($franchise->seasons) && count($franchise->seasons) > 0) {
        foreach ($franchise->seasons as $key => $value) {
            $nextSeasonKey = $key+1;
            if (rljeApiWP_convertSeasonNameToURL($value->name) === $seasonName
                && $nextSeasonKey < count($franchise->seasons)) {
                $firstEpisode = isset($franchise->seasons[$nextSeasonKey]->episodes[0]->name) ? $franchise->seasons[$nextSeasonKey]->episodes[0]->name : '';
                $firstEpisodeInNextSeasonURL = '/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($franchise->seasons[$nextSeasonKey]->name).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($firstEpisode);
                break;
            }
        }
    }
    return $firstEpisodeInNextSeasonURL;
}

/**
 * REMOVE OR CHANGE THIS METHOD WHEN RELATED PAGE WILL BE DEFINED, FOR NOW IS USING CATEGORY TO FILTER.
 *
 * Retrieve the franchise related to a particular franchise by category.
 * @param String $category Category id to get all the francheases with this category from the api service.
 * @return array
 */
function rljeApiWP_getFranchiseRelatedByCategory($category) {
    $related = Related::getInstance();
    $franchisesRelated = $related->getFranchiseRelatedByCategory($category);
    return $franchisesRelated;
}

/**
 * Retrieve the franchises that contains the text searched.
 * @param String $searchText Text to search.
 * @return Array Object | Null
 */
function rljeApiWP_searchByFranchises($searchText) {
    $search = Search::getInstance();
    return $search->getByFranchises($searchText);
}

/**
 * Retrieve the episodes that contains the text searched.
 * @param String $searchText Text to search.
 * @return Array Object | Null
 */
function rljeApiWP_searchByEpisodes($searchText) {
    $search = Search::getInstance();
    return $search->getByEpisodes($searchText);
}

/**
 * Get all the franchises filtering by person name.
 * @param String $searchPerson Person name to search.
 * @return Array Object | Null
 */
function rljeApiWP_searchFranchiseByPerson($searchPerson) {
    $search = Search::getInstance();
    return $search->getFranchisesByPerson($searchPerson);
}

/**
 * Retrieve the Recently Watched list by sessionId.
 * @param String $sessionId Session id to get all the recently watched list from the api service by sessionId.
 * @return Array Object | Null
 */
function rljeApiWP_getUserRecentlyWatched($sessionId) {
    $user = User::getInstance();
    return $user->getUserRecentlyWatched($sessionId);
}


/**
 * Retrieve the Watchlist by sessionId.
 * @param String $sessionId Session id to get all the recently watched list from the api service by sessionId.
 * @return Array Object | false
 */
function rljeApiWP_getUserWatchlist($sessionId) {
    $user = User::getInstance();
    return $user->getUserWatchlist($sessionId);
}

/**
 * Retrieve if the user logged is active by sessionId.
 * @param String $sessionId Session id.
 * @return boolean
 */
function rljeApiWP_isUserActive($sessionId) {
    $keyInCache = 'userStatus_'.md5($sessionId);
    $userStatus = wp_cache_get($keyInCache, 'userStatus');
    if (false === $userStatus) {
        $userProfile = rljeApiWP_getUserProfile($sessionId);
        if($userProfile && isset($userProfile->Membership->Status) && strtolower($userProfile->Membership->Status) == 'active') {
            $userStatus = 'active';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } elseif($userProfile && isset($userProfile->Membership->Status) && strtolower($userProfile->Membership->Status) == 'expired') {
            $userStatus = 'expired';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } else {
            $userStatus = 'inactive';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }

    return ($userStatus === 'active') ? true : false;
}

/**
 * Determines if user has subscription
 * @param String $sessionId Session id.
 * @return boolean
 */
function rljeApiWP_isUserEnabled($sessionId) {
    $keyInCache = 'userStatus_'.md5($sessionId);
    $userStatus = wp_cache_get($keyInCache, 'userStatus');
    if (false === $userStatus) {
        $userProfile = rljeApiWP_getUserProfile($sessionId);
        if($userProfile && isset($userProfile->Membership->Status) && strtolower($userProfile->Membership->Status) == 'active') {
            $userStatus = 'active';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } elseif($userProfile && isset($userProfile->Membership->Status) && strtolower($userProfile->Membership->Status) == 'expired') {
            $userStatus = 'expired';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } else {
            $userStatus = 'inactive';
            wp_cache_set($keyInCache, $userStatus, 'userStatus', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }

    return in_array($userStatus, ['active', 'expired']);
}

/**
 * Determines if user has exceeded concurrent view streams
 * @param String $sessionId Session id.
 * @return boolean
 */
function rljeApiWP_isUserAboveConcurrentStreams($sessionId) {
    $user = User::getInstance();
    $streamStatus = $user->concurrentStreams($sessionId);
    if(isset($streamStatus->Streaming->Exceeded)) {
        return (bool) $streamStatus->Streaming->Exceeded;
    } else {
        return false;
    }
}

/**
 * Add a franchise to watchlist by SessionId
 * @param String $franchiseId
 * @param String $sessionId
 * @return Array Object | null
 */
function rljeApiWP_addToWatchlist($franchiseId, $sessionId) {
    $user = User::getInstance();
    return $user->addToWatchlist($franchiseId, $sessionId);
}

/**
 * Remove a franchise from the watchlist by SessionId
 * @param String $franchiseId
 * @param String $sessionId
 * @return Array Object | null
 */
function rljeApiWP_removeFromWatchlist($franchiseId, $sessionId) {
    $user = User::getInstance();
    return $user->removeFromWatchlist($franchiseId, $sessionId);
}

/**
 * Checks if a franchise exist in the user's watchlist by sessionId.
 * @param String $franchiseId Franchise id.
 * @param String $sessionId Session id.
 * @return boolean
 */
function rljeApiWP_isFranchiseAddedToWatchlist($franchiseId, $sessionId) {
    $user = User::getInstance();
    return $user->isFranchiseAddedToWatchlist($franchiseId, $sessionId);
}

/**
 * Get the stream positions about a franchise watched by SessionId
 * @param String $franchiseId
 * @param String $sessionId
 * @return Array Object | null
 */
function rljeApiWP_getStreamPositionsByFranchise($franchiseId, $sessionId) {
    $user = User::getInstance();
    return $user->getStreamPositionsByFranchise($franchiseId, $sessionId);
}

/**
 * Add a stream position about an episode watched by SessionId
 * @param String $episodeId
 * @param String $sessionId
 * @param String $position
 * @param String $lastKnownAction
 * @return Array Object | null
 */
function rljeApiWP_addStreamPosition($episodeId, $sessionId, $position, $lastKnownAction) {
    $user = User::getInstance();
    return $user->addStreamPosition($episodeId, $sessionId, $position, $lastKnownAction);
}

/**
 * Convert seconds to min:sec format
 * @param int $seconds Seconds to convert to min:secs format
 * @return string Time in min:secs format
 */
function rljeApiWP_convertSecondsToMinSecs($seconds = 0) {
    $mins = floor($seconds / 60);
    $secs = $seconds - ($mins * 60);
    return sprintf("%02d:%02d", $mins, $secs);
}

/**
 * Gets Base URL Path of the site.
 * @return string URL path with protocol and server name of the site.
 */
function rljeApiWP_getBaseUrlPath() {
    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    return $protocol.'://'.$_SERVER["SERVER_NAME"];
}

/**
 * Gets User profile.
 * IMPORTANT: This is using transient because of if the W3TC Object Cache is
 * disabled will save and keep the profile information in the database.
 * Keep on mind the use of transients for futher cases where the data should be
 * keeped in all the site, caching in database instead of memory.
 * @param string $sessionId
 * @return string URL path with protocol and server name of the site.
 */
function rljeApiWP_getUserProfile($sessionId) {
    $key = 'atv_userProfile_'.md5($sessionId);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $userProfile = get_transient($cacheKey);
    if (false === $userProfile) {
        $user = User::getInstance();
        $getUserProfile = $user->getUserProfile($sessionId);
        if(!empty($getUserProfile)){
            $userProfile = $getUserProfile;
            set_transient($cacheKey, $userProfile, RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }
    return $userProfile;
}

/**
 * Gets Base URL Path of the site.
 * @param string $sessionId
 * @return string URL path with protocol and server name of the site.
 */
function rljeApiWP_getUserEmailAddress($sessionId) {
    $key = 'userEmail_'.md5($sessionId);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $emailAddress = wp_cache_get($cacheKey, 'userEmail');
    if (false === $emailAddress) {
        $user = User::getInstance();
        $emailAddress = '';
        $userProfile = rljeApiWP_getUserProfile($sessionId);
        if(isset($userProfile->Customer->Email)){
            $emailAddress = $userProfile->Customer->Email;
            wp_cache_set($cacheKey, $emailAddress, 'userEmail', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }
    return $emailAddress;
}

/**
 * Get webPaymentEdit from initializeApp json.
 * @param string $sessionId
 * @return boolean
 */
function rljeApiWP_getWebPaymentEdit($sessionId) {
    $keyInCache = 'webPaymentEdit_'.md5($sessionId);
    $webPaymentEdit = wp_cache_get($keyInCache, 'userWebPayment');
    if (false === $webPaymentEdit) {
        $user = User::getInstance();
        $userProfile = rljeApiWP_getUserProfile($sessionId);
        if(isset($userProfile->Membership->WebPaymentEdit) && $userProfile->Membership->WebPaymentEdit) {
            $webPaymentEdit = 'enabled';
            wp_cache_set($keyInCache, $webPaymentEdit, 'userWebPayment', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
        else {
            $webPaymentEdit = 'disabled';
            wp_cache_set($keyInCache, $webPaymentEdit, 'userWebPayment', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        }
    }
    return ($webPaymentEdit === 'enabled') ? true : false;
}


/**
 * Get stripeCustomerID from profile json.
 * @param string $sessionId
 * @return string | false
 */
function rljeApiWP_getStripeCustomerId($sessionId) {
    $keyInCache = 'stripeCustomerId_'.md5($sessionId);
    $stripeCustomerId = wp_cache_get($keyInCache, 'userStripeCustomerID');
    if (false === $stripeCustomerId) {
        $user = User::getInstance();
        $userProfile = rljeApiWP_getUserProfile($sessionId);
        if(isset($userProfile->Membership->StripeEnabled, $userProfile->Customer->StripeCustomerID) && $userProfile->Membership->StripeEnabled == true) {
            $stripeCustomerId = $userProfile->Customer->StripeCustomerID;
        }
        else {
            $stripeCustomerId = false;
        }
        wp_cache_set($keyInCache, $stripeCustomerId, 'userStripeCustomerID', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }
    return $stripeCustomerId;
}

/**
 * Send the contact form data to api rest service.
 * @param array $formData Contact form data.
 * @return boolean.
 */
function rljeApiWP_contactFormData($formData) {
    $user = User::getInstance();
    $isContactFormSent = false;
    $sendingContactFormData = $user->sendContactFormData($formData);
    if($sendingContactFormData) {
        $isContactFormSent = true;
    }
    return $isContactFormSent;
}


/**
 * Send the email address to signup the newsletter.
 * @param string $email Email address to subscribe.
 * @return boolean.
 */
function rljeApiWP_signupNewsletter($email) {
    $user = User::getInstance();
    $isSubscribed = false;
    if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $user->signupNewsletter($email)) {
        $isSubscribed = true;
    }
    return $isSubscribed;
}

/**
 * Get all the franchise items according to a categories list.
 * @param array $categoriesList Categories list to get all the franchise items.
 * @param string $sessionId User session id.
 * @return array | boolean.
 */
function rljeApiWP_getBrowseAllBySection($categoriesList = array(), $sessionId = null) {
    $key = 'browseAll_items_'.strlen(serialize($categoriesList));
    $cacheKey = rljeApiWP_getCacheKey($key);
    $franchisesItems = wp_cache_get($cacheKey, 'browse', true);
    if (false === $franchisesItems && 0 < count($categoriesList)) {
        $franchisesItems = array();
        foreach ($categoriesList as $key=>$property) {
            $franchisesItems = array_merge($franchisesItems, rljeApiWP_getItemsByCategoryOrCollection(urlencode(strtolower($categoriesList[$key]))));
        }
        $franchisesItems = rljeApiWP_getUniqueFranchiseItems($franchisesItems);
        wp_cache_set($cacheKey, $franchisesItems, 'browse', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }
    return $franchisesItems;
}

function rljeApiWP_getBrowseAll() {
    $key = 'browseAll_items_all';
    $cacheKey = rljeApiWP_getCacheKey($key);
    $browseAllItems = wp_cache_get($cacheKey, 'browse', true);
    if (false === $browseAllItems) {
        $browseAllItems = rljeApiWP_getBrowseAllItems($cacheKey);
        wp_cache_set($cacheKey, $browseAllItems, 'browse', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }
    return $browseAllItems;
}

/**
 * Filter the franchise items duplicated and return it ordered by name alphabetically.
 * @param array $franchisesItems Franchise items list.
 * @return array.
 */
function rljeApiWP_getUniqueFranchiseItems($franchisesItems = array()) {
    $resultFranchises = $uniqueFranchiseIds = array();

    foreach($franchisesItems as $val) {
        if (!empty($val->id) && !in_array($val->id, $uniqueFranchiseIds)) {
            $uniqueFranchiseIds[] = $val->id;
            $resultFranchises[] = $val;
        }
    }
    return $resultFranchises;
}

/**
 * Get the franchises ordered by created date.
 * @param array $franchisesItems Franchise items list.
 * @return array.
 */
function rljeApiWP_orderFranchisesByCreatedDate($franchisesItems = array()) {
    $key = 'orderFranchisesItems_'.strlen(serialize($franchisesItems));
    $cacheKey = rljeApiWP_getCacheKey($key);
    $franchisesResultItems = wp_cache_get($cacheKey, 'browse_orderby', true);
    if (false === $franchisesResultItems) {
        //Franchises ordered by created date.
        $franchisesResultItems = $franchisesItems;
        usort($franchisesResultItems, function($a, $b) {
            $result = -1;
            if(isset($a->createdDate,$b->createdDate)) {
                $result = $a->createdDate < $b->createdDate;
            }
            return $result;
        });
        wp_cache_set($cacheKey, $franchisesResultItems, 'browse_orderby', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $franchisesResultItems;
}

/**
 * Get franchise items ordered by name alphabetically.
 * @param array $franchisesItems Franchise items list.
 * @return array.
 */
function rljeApiWP_getFranchiseItemsOrderByName($franchisesItems = array()) {
    $resultFranchises = array();

    foreach($franchisesItems as $val) {
        $resultFranchises[] = preg_replace("/^The\s(.+)/i", '$1', $val->name);

    }

    //Franchises ordered by name alphabetically.
    usort($resultFranchises, function($a, $b) {
        return strcmp($a, $b);
    });

    return $resultFranchises;
}

/**
 * Get Franchises related according the viewers also watched criteria.
 * @param array $franchiseId Franchise ID.
 * @return  array object | null
 */
function rljeApiWP_getViewersAlsoWatched($franchiseId) {
    $keyCache = 'viewer_also_watch_' . md5($franchiseId);
    $viewerAlsoWatch = wp_cache_get($keyCache, 'franchises');
    if (false === $viewerAlsoWatch) {
        $related = Related::getInstance();
        $viewerAlsoWatch =  $related->getViewersAlsoWatched($franchiseId);
        wp_cache_set($keyCache, $viewerAlsoWatch, 'franchises', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $viewerAlsoWatch;
}

/**
 * Set the future date in a Cookie.
 * @param date $date Set date
 * @return Date String | false
 */
function rljeApiWP_setFutureDate($date) {
    $resource = Resource::getInstance();
    $futureDate = $resource->setFutureDate($date);
    return $futureDate;
}

/**
 * Get the future date from Cookie if it exists else return false.
 * @return Date String | false
 */
function rljeApiWP_getFutureDate() {
    $resource = Resource::getInstance();
    $futureDate = $resource->getFutureDate();
    return ($futureDate !== 'today') ? $futureDate : false;
}

/**
 * Get cache key to use in the wp_cache checking if any special feature was set
 * to return the cache key for that content.
 * @param string $key Cache key set by default.
 * @return string
 */
function rljeApiWP_getCacheKey($key) {
    $cacheKey = $key;
    $objResources = Resource::getInstance();
    $futureDate = $objResources->getFutureDate();
    $countryToFilter = $objResources->getCountryCode();
    // Add future date cache key if it is set.
    if($futureDate !== 'today'){
        $cacheKey .= '_'.$futureDate;
    }
    // Add country filter cache key if it is set.
    if(!empty($countryToFilter)) {
        $cacheKey .= '_'.$countryToFilter;
    }
    return $cacheKey;
}

/**
 * Set the Country Filter in a Cookie.
 * @param string $code Country Code to save in the cookie.
 * @return string | false
 */
function rljeApiWP_setCountryFilter($code) {
    $resource = Resource::getInstance();
    $setCountryCode = $resource->setCountryToFilter($code);
    return $setCountryCode;
}

/**
 * Get the Country Filter from Cookie if it exists else return false.
 * @return string | false
 */
function rljeApiWP_getCountryFilter() {
    $resource = Resource::getInstance();
    $countryCode = $resource->getCountryToFilter();
    return (!empty($countryCode)) ? $countryCode : false;
}

/**
 * Set Video Debugger in a Cookie.
 * @param string $status Status to save in the cookie.
 * @return string | false
 */
function rljeApiWP_setVideoDebugger($status) {
    $resource = Resource::getInstance();
    $setCountryCode = $resource->setVideoDebugger($status);
    return $setCountryCode;
}

/**
 * Checks if Video Debugger is On from Cookie.
 * @return boolean
 */
function rljeApiWP_isVideoDebuggerOn() {
    $resource = Resource::getInstance();
    return $resource->isVideoDebuggerOn();
}

/**
 * Get the Country Code from Server or Cookie if it exists. Only will returns the code if it is outside of US.
 * @return string | false
 */
function rljeApiWP_getCountryCode() {
    $resource = Resource::getInstance();
    $countryCode = $resource->getCountryCode();
    return (!empty($countryCode) && strtolower($countryCode) !== 'us') ? $countryCode : false;
}

/**
 * Get the content for Homepage.
 * @return array object | null
 */
function rljeApiWP_getHomeItems($sectionId=null, $categoryId=null) {
    $key = 'home_items_' . md5($sectionId.'#'.$categoryId);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $homeItems = wp_cache_get($cacheKey, 'home_franchises');
    if (false === $homeItems) {
        $resource = Content::getInstance();
        $homeItems = $resource->getHomeItems();

        //Returns a specific section or category if it is set.
        if(!empty($sectionId)) {
            $homeItems = rljeApiWP_getSectionOrCategoryFromAPIResponse($homeItems, $sectionId, $categoryId);
        }
        wp_cache_set($cacheKey, $homeItems, 'home_franchises', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $homeItems;
}

/**
 * Get the content for Browse page.
 * @return array object | null
 */
function rljeApiWP_getBrowseItems($sectionId=null, $categoryId=null) {
    $key = 'browse_items_' . md5($categoryId);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $browseItems = wp_cache_get($cacheKey, 'browse_franchises');
    if (false === $browseItems) {
        $resource = Content::getInstance();
        $browseItems = $resource->getBrowseItems();

        //Returns a specific category if it is set.
        if(!empty($sectionId)) {
            $browseItems = rljeApiWP_getSectionOrCategoryFromAPIResponse($browseItems, $sectionId, $categoryId);
        }
        wp_cache_set($cacheKey, $browseItems, 'browse_franchises', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $browseItems;
}

/**
 * Get the section or category by ID from an API response.
 * @return array object | null
 */
function rljeApiWP_getSectionOrCategoryFromAPIResponse($APIresponse, $sectionId=null, $categoryId=null) {
    $result = null;
    if(0 < count($APIresponse)) {
        foreach ($APIresponse as $APIitem) {
            if(isset($APIitem->id) && strtolower($sectionId) == strtolower($APIitem->id)) {
                $result = $APIitem;
                //Returns a specific category if it is set.
                if(!empty($categoryId)) {
                    foreach($APIitem->options as $option) {
                        if(isset($option->id) && strtolower($option->id) == strtolower($categoryId)) {
                            $result = $option;
                            break;
                        }
                    }
                }
                break;
            }
        }
    }
    return $result;
}

/**
 * Get the content by pagination.
 * @return array object | null
 */
function rljeApiWP_getContentPageItems($content=null, $page=null) {
    $resource = Content::getInstance();
    $key = 'contentPage_items_'.md5($content.'-'.$page);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $contentPageItems = wp_cache_get($cacheKey, 'contentPage_items');
    if (false === $contentPageItems && isset($content, $page)) {
        $contentPageItems = $resource->getContentPageItems($content, $page);
        wp_cache_set($cacheKey, $contentPageItems, 'contentPage_items', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
    }

    return $contentPageItems;
}

/**
 * Retrieve all the available localization.
 * @param String $type of localization to get the available localization.
 * @return array
 */
function rljeApiWP_getLocale($type = 'native') {
    $key = 'locale_' . md5($type);
    $cacheKey = rljeApiWP_getCacheKey($key);
    $results = wp_cache_get($cacheKey, 'I18N', true);
    if (false === $results) {
        $i18n = I18N::getInstance();
        $results = $i18n->getLocale($type);
        if (isset($results)) {
            wp_cache_set($cacheKey, $results, 'I18N', RLJE_API_PLUGIN__TIME_REFRESH_CACHE);
        } else {
            $results = array();
        }
    }

    return $results;
}
