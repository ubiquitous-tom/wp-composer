<?php
get_header();
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
if (function_exists('rljeApiWP_getFranchiseById')) :
    $haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'franchise');
    if($haveFranchisesAvailable) :
        $environment = apply_filters('atv_get_extenal_subdomain', '');

        $franchiseId = $wp_query->query_vars['franchise_id'];

        $franchise = rljeApiWP_getFranchiseById($franchiseId);

        $streamPositions = null;

        if (isset($franchise->id)) :
            if (isset($_COOKIE["ATVSessionCookie"]) && rljeApiWP_isUserActive($_COOKIE["ATVSessionCookie"])) {
                $getStreamPositions = rljeApiWP_getStreamPositionsByFranchise($franchiseId, $_COOKIE["ATVSessionCookie"]);
                if(isset($getStreamPositions->streamPositions)) {
                    $streamPositions = [];
                    foreach ($getStreamPositions->streamPositions as $streamPosition) {
                        $streamPositions[$streamPosition->EpisodeID] = [
                            "Position" => $streamPosition->Position,
                            "EpisodeLength" => $streamPosition->EpisodeLength
                        ];
                    }
                }
            }
            $totalEpisodes = 0;
            foreach ($franchise->seasons as $seasonItem) {
                $totalEpisodes+= count($seasonItem->episodes);
            }
?>

<div class="secondary-bg">
    <div class="container franchise">
      <div class="col-md-12" itemscope itemtype="http://schema.org/TVSeries">
        <h4 class="subnav">
            <!-- Previous link -->
            <span class="subnav-prev hidden-xs hidden-sm">
                <a href="<?= $baseUrlPath.'/'.$franchise->id; ?>">
                    <img src="<?php echo apply_filters('atv_get_image_url', 'left-arrow?t=Icons'); ?>" id="archive-arrows">
                    <span>Back to Series</span>
                </a>
            </span>
            <a href="<?= $baseUrlPath.'/'.$franchiseId?>" id="subnav-title"><span itemprop="name"><?= $franchise->name; ?></span></a> Trailer   <!-- Next link -->
            <meta itemprop="image" content="<?php echo apply_filters('atv_get_image_url', $franchise->image); ?>" />
            <meta itemprop="description" content="<?= $franchise->longDescription; ?>" />
            <meta itemprop="numberOfEpisodes" content="<?= $totalEpisodes; ?>" />
            <meta itemprop="numberOfSeasons" content="<?= count($franchise->seasons)?>" />

            <span class="subnav-next hidden-xs hidden-sm">
              <?php if(isset($franchise->seasons[0], $franchise->seasons[0]->episodes[0])) :?>
              <a href="<?php echo esc_url( trailingslashit( $baseUrlPath.'/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($franchise->seasons[0]->name).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($franchise->seasons[0]->episodes[0]->name) ) ); ?>">
                  <span>Watch Episode</span>
                  <img src="<?php echo apply_filters('atv_get_image_url', 'right-arrow?t=Icons'); ?>" id="archive-arrows">
              </a>
              <?php endif;?>
            </span>
        </h4>
        <!-- Brightcove Episode Player -->
        <div class="outer-container episode-player">
        <?php
            if(isset($franchise->episodes[0]->id)&& is_numeric($franchise->episodes[0]->id)) :
                $trailerId = $franchise->episodes[0]->id;
        ?>
            <span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
                <meta itemprop="thumbnailUrl" content="<?php echo apply_filters('atv_get_image_url', $franchise->image.'?w=750'); ?>" />
                <meta itemprop="description" content="<?= $franchise->longDescription; ?>" />
                <meta itemprop="name" content="<?= $franchise->name; ?>" />
                <meta itemprop="uploadDate" content="<?= (isset($franchise->episodes[0]->startDate) && $franchise->episodes[0]->startDate != '') ? date('Y-m-d', $franchise->episodes[0]->startDate) : ''; ?>" />
                <div id="trailer-video" class="video" data-embedcode="<iframe style='border:none;z-index:4' src='//players.brightcove.net/3392051363001/2f9624d6-0dd2-46ff-9843-dadffb653bc3_default/index.html?videoId=<?= $trailerId; ?>'
                    allowfullscreen
                    webkitallowfullscreen
                    mozallowfullscreen></iframe>">
                    <img title="image title" alt="thumb image" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $franchise->image.'?w=750'); ?>"/>

                    <script>
                    (function() {

                        var video = document.querySelector('#trailer-video');
                        var embedcode = video.dataset.embedcode;
                        var hasvideo = video.dataset.hasvideo;

                        // Only append the video if it isn't yet appended.
                        if (!hasvideo) {
                          video.insertAdjacentHTML('afterbegin', embedcode);
                          video.dataset.hasvideo = "true";
                        }
                    })();
                    </script>
                </div>
            </span>
        <?php
            else:
        ?>
            <img title="Play trailer" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $franchise->image.'?w=750'); ?>"/>
            <div class="acorntv-slogan">
                <h3>Watch world-class TV from Britain and beyond</h3>
                <h4>Always available, always commercial free</h4>
                <a class="free-month" href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html">Start Free Trial</a>
            </div>
        <?php
            endif;
        ?>
        </div>
      </div>
    </div>
</div>

<!-- Episode content begins (descriptions, tags, more episodes, and related titles) -->
<div class="container episode">
    <!-- More Episodes Carousel -->
    <!-- Multiple carousels. If there is more than four episodes use bootstrap carousel-->

    <div class="col-md-12">
        <h4 class="subnav2" >Episodes</h4>
        <?php
            $wp_query->query_vars['franchiseName'] = $franchise->name;
            $isLessThan4Episodes = apply_filters('atv_is_less_than_4_episodes', $franchise->seasons);

            if($isLessThan4Episodes) {
                $wp_query->query_vars['season'] = $franchise->seasons[0];
                $wp_query->query_vars['ignoreSeasonHeader'] = true;
                get_template_part('partials/list-episode-items');
            }
            else {
                $wp_query->query_vars['current_episode_id'] = $franchise->seasons[0]->episodes[0]->id;
                $wp_query->query_vars['seasons_carousel'] = $franchise->seasons;
                $wp_query->query_vars['streamPositions'] = $streamPositions;
                get_template_part('partials/more-episodes-carousel');
            }
        ?>
    </div>

    <!-- Multiple carousels. If there is less than four episodes display basic column grid-->


    <!-- You May Also Like Carousel -->
    <?php
        $wp_query->query_vars['also_watched_items'] = rljeApiWP_getViewersAlsoWatched($franchiseId);
        get_template_part('partials/viewers-also-watched');
    ?>
</div>
<?php
        else :
            get_template_part('partials/no-result-message');
        endif;
    else:
        get_template_part('templates/franchisesUnavailable');
    endif;
else :
    get_template_part('partials/plugin-deactivated-message');
endif;
get_footer();
