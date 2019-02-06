<?php 
    if(!empty($_COOKIE['ATVSessionCookie']) && rljeApiWP_isUserActive($_COOKIE['ATVSessionCookie'])):
        $watchSpotlightItems = apply_filters('atv_get_watch_spotlight_items', 'recentlyWatched');
        if(0 < count($watchSpotlightItems)):
?>
<!-- RECENTLY WATCHED || WATCHLIST SPOTLIGHT-->
<div class="col-md-12">
    <?php 
        set_query_var('carousel-items', $watchSpotlightItems);
        get_template_part('partials/section-generic-carousel');
    ?>
</div>
<?php 
        endif;
    endif;
    
    $spotlightItems = get_query_var('spotlight_items');
    foreach ($spotlightItems as $spotlight) :
        $spotlightName = (!empty($spotlight->name)) ? $spotlight->name : '';
?>
<!-- <?php echo strtoupper($spotlightName); ?> SERIES SPOTLIGHT-->
<div class="col-md-12">
    <?php         
        set_query_var('carousel-section', array(
            'title' => $spotlightName,
            'categoryObj' => $spotlight
        ));
        get_template_part('partials/section-carousel-pagination');
    ?>
</div>
<?php endforeach; ?>