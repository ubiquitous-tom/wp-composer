<?php
if (function_exists('rljeApiWP_getItemsByCategoryOrCollection')) :
    $rljeApiWP_isUserActive = false;
    $atvSessionCookie = null;
    if(!empty($_COOKIE['ATVSessionCookie']) && rljeApiWP_isUserActive($_COOKIE['ATVSessionCookie'])) {
        $atvSessionCookie = $_COOKIE['ATVSessionCookie'];
        $rljeApiWP_isUserActive = true;
    }

    $listSections = array();
    $guideItems = array();

    $guideObj = rljeApiWP_getBrowseItems('guide');
    if(!empty($guideObj->options) && is_array($guideObj->options)) {
        $guideItems = $guideObj->options;
        foreach ($guideItems as $guide) {
            $browseId = apply_filters('atv_get_browse_section_id', $guide->id);
            $listSections[$browseId] = $guide->name;
        }
    }


    //Set active section
    $activeSection = get_query_var('section');

    $isOrderByEnabled = true;

    if(empty($activeSection) || $activeSection === 'recentlywatched' || $activeSection === 'yourwatchlist') {
        $isOrderByEnabled = false;
    }

    if($rljeApiWP_isUserActive) {
        $listSections = array_merge(
            array(
                'recentlywatched' => 'Recently Watched',
                'yourwatchlist' => 'My Watchlist'
            ),
            $listSections
        );
    }
    elseif ($activeSection === 'recentlywatched' || $activeSection === 'yourwatchlist'){
        wp_safe_redirect( home_url('browse') );
        exit;
    }
    $listSections = array_merge(array('all' => 'All Shows'), $listSections); //Add All Shows always as first item.

    if(isset($listSections[$activeSection]) || empty($activeSection)):
get_header();
?>
<!-- Filter JS Sub Navigation base on category -->
<section class="browse">
    <div class="container">
        <ul class="subnav">
            <?php foreach ($listSections as $key=>$property) : ?>
            <li class="browse-menu<?php echo ($key===$activeSection)?' active':''; ?>"><a href="/browse/<?= $key; ?>"><?= $property; ?></a></li>
            <?php endforeach;?>
        </ul>
        <?php
            $haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'section');
            if($haveFranchisesAvailable) :
                if($isOrderByEnabled) :
        ?>
        <div id="page-subhead" class="browse-order">
            <span>SORT BY:</span>
            <a class="browse-order-option active js-orderby-added" href="#date-added">Date Added</a>
            <a class="browse-order-option js-orderby-az" href="#a-z">A to Z</a>
        </div>
        <?php
                endif;
                if(empty($activeSection)):
                    // Show initial Browse Page
                    set_query_var('spotlight_items', $guideItems);
                    get_template_part('partials/browse-initial-carousel');
                else:
                    // Show Browse Page filtered
        ?>
        <!-- Category Content Column Grid -->
        <div class="objects" >
            <?php
                    $arrayListItems = array();
                    switch ($activeSection) {
                        case 'all':
                            unset($listSections['all']);
                            if($rljeApiWP_isUserActive) {
                                unset($listSections['recentlywatched']);
                                unset($listSections['yourwatchlist']);
                            }
                            foreach ($listSections as $key=>$value ) {
                                $listSectionKeys[] = apply_filters('atv_convert_browseSlug_to_contentID', $key);
                            }
                            $listItems = rljeApiWP_getBrowseAllBySection($listSectionKeys, $atvSessionCookie);
                            $arrayListItems = rljeApiWP_orderFranchisesByCreatedDate($listItems);
                            break;
                        case 'recentlywatched':
                            $arrayListItems = rljeApiWP_getUserRecentlyWatched($atvSessionCookie);
                            break;
                        case 'yourwatchlist':
                            $arrayListItems = rljeApiWP_getUserWatchlist($atvSessionCookie);
                            break;
                        default:
                            $listItems = rljeApiWP_getItemsByCategoryOrCollection(apply_filters('atv_convert_browseSlug_to_contentID', $activeSection));
                            $arrayListItems = rljeApiWP_orderFranchisesByCreatedDate($listItems);
                            break;
                    }
                    set_query_var('array_list_items', $arrayListItems);
            ?>
                    <div class="item" style="margin-left:0px;width:100%;padding-bottom:30px;">
                        <?php get_template_part('partials/list-browse-items'); ?>
                    </div>
        </div>
        <?php
                endif;
            else :
                 get_template_part('partials/franchises-unavailable-message');
            endif;
        ?>
    </div>
</section>
<?php
get_footer();
    else:
         require_once(get_404_template());
    endif;
else :
    get_header();
    get_template_part('partials/plugin-deactivated-message');
    get_footer();
endif;
