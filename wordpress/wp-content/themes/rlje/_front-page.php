<?php
$haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'homepage');
$categoriesHome = rljeApiWP_getHomeItems('categories');
$categoriesItems = (isset($categoriesHome->options)) ? $categoriesHome->options : array();
$browseIdListAvailables = apply_filters('atv_get_browse_genres_availables', '');

if($haveFranchisesAvailable) :
    get_header();
?>
<!-- HERO CAROUSEL -->
<section class="home-hero-carousel">
    <?php 
        get_template_part('partials/homepage-herocarousel');
    ?> 
</section>

<section class="home-featured">
  <div class="container">
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
        
        for($i=0; $i<2 && isset($categoriesItems[$i]); $i++):
          $spotlight = $categoriesItems[$i];
          $spotlightName = (!empty($spotlight->name)) ? $spotlight->name : '';
    ?>
    <!-- <?php echo strtoupper($spotlightName); ?> SPOTLIGHT-->
    <div class="col-md-12">
        <?php 
            set_query_var('carousel-section', array(
                'title' => $spotlightName,
                'categoryObj' => $spotlight,
                'showViewAllLink' => (isset($browseIdListAvailables[$spotlight->id]))
            ));
            get_template_part('partials/section-carousel-pagination');
        ?>    
    </div>
    <?php 
        endfor;
    ?>
  </div>
</section>

<?php if(!rljeApiWP_getCountryCode()): ?>
<section class="home-middle hidden-xs hidden-sm">
    <div class="container">
        <div class="row">
            <h4 class="subnav" style="padding-bottom:10px;margin-bottom:0px;">News &amp; Reviews</h4>
            <!-- MARKETING PLACEHOLDER -->
            <?php 
                get_template_part('partials/homepage-section-marketing-placeholder');
            ?>

            <!-- LATEST NEWS -->
            <?php 
                get_template_part('partials/homepage-section-lastnews');
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="home-spotlights">
  <div class="container">
    <?php 
        for($i=2; $i<count($categoriesItems); $i++) :
          $spotlight = $categoriesItems[$i];
          $spotlightName = (!empty($spotlight->name)) ? $spotlight->name : '';
    ?>
  <!-- <?php echo strtoupper($spotlightName); ?> SPOTLIGHT -->
    <div class="col-md-12">
        <?php 
            set_query_var('carousel-section', array(
                'title' => $spotlightName,
                'categoryObj' => $spotlight,
                'showViewAllLink' => (isset($browseIdListAvailables[$spotlight->id]))
            ));
            get_template_part('partials/section-carousel-pagination');
        ?>
    </div>
    <?php 
        endfor;
    ?>
  </div>
</section>

<!-- CALLOUTS -->
<section class="home-callout">
    <div class="container">
      <div class="col-md-12 home-callout-body">
        <div class="col-md-6" id="border-carousel">
          <div class="home-callout-content">
            <img src="https://api.rlje.net/acorn/artwork/size/devices-icon?t=Icons" id="home-devices-img">
            <p class="home-callout-description">Available on Roku, Apple TV, Samsung Smart TV, iPhone, iPad, web and more. </p>
            <a href="http://www2.acorn.tv/how-to-watch/">
                <button>Learn More</button>
            </a>
          </div>
        </div>

        <div class="col-md-6" style="padding:0px 30px;padding-bottom:50px;">
          <div class="home-callout-content">
            <img src="https://api.rlje.net/acorn/artwork/size/signup-icon?t=Icons" id="home-trial-img">
            <p class="home-callout-description">Over 1,800 hours of programming, including 60 shows you won't find anywhere else. </p>
            <?php 
                $environment = apply_filters('atv_get_extenal_subdomain', '');
            ?>
            <a href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html">
                <button>Start Your Free Trial</button>
            </a>
          </div>
        </div>
      </div>
    </div>
</section>
<?php 
    get_footer();
else : 
    get_template_part('templates/franchisesUnavailable');
endif;
