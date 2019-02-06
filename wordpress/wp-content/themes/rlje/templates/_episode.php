<?php
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
if (function_exists('rljeApiWP_getFranchiseById')) :
    $environment = apply_filters('atv_get_extenal_subdomain', '');

    $franchiseId = get_query_var('franchise_id');
    $seasonNameURL = get_query_var('season_name');
    $episodeNameURL = get_query_var('episode_name');

    $franchise = rljeApiWP_getFranchiseById($franchiseId);
    $season = rljeApiWP_getCurrentSeason($franchiseId, $seasonNameURL);
    $episode = rljeApiWP_getCurrentEpisode($franchiseId, $seasonNameURL, $episodeNameURL);

    $prevEpisodeURL = rljeApiWP_getPreviousEpisodeURL($franchiseId, $seasonNameURL, $episodeNameURL);
    $nextEpisodeURL = rljeApiWP_getNextEpisodeURL($franchiseId, $seasonNameURL, $episodeNameURL);
    $nextEpisodeData = apply_filters('atv_get_next_episode_data', $franchise, $season->id, $episode->id);
    $streamPositions = null;
    $isCast = (isset($episode->actors) && count($episode->actors) >0) ? true : false;
    $isVideoDebuggerOn = rljeApiWP_isVideoDebuggerOn();

    $totalEpisodes = 0;
    foreach ($franchise->seasons as $seasonItem) {
        $totalEpisodes+= count($seasonItem->episodes);
    }

    if (isset($episode->id)) :
        $atvSessionCookie = (isset($_COOKIE["ATVSessionCookie"])) ? $_COOKIE["ATVSessionCookie"] : false;
        $isUserActive = ($atvSessionCookie && rljeApiWP_isUserActive($atvSessionCookie));
        if ($isUserActive) {
            $watchlist = rljeApiWP_getUserWatchlist($atvSessionCookie);
            $getStreamPositions = rljeApiWP_getStreamPositionsByFranchise($franchiseId, $atvSessionCookie);
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
        get_header();
?>

<script>dataLayer=[];</script>

<div class="secondary-bg">
    <div class="container franchise">
      <div id="episode" class="col-md-12" itemscope itemtype="http://schema.org/TVEpisode">
        <h4 class="subnav">
          <!-- Previous link -->
          <span class="subnav-prev hidden-xs hidden-sm">
              <span> <img src="<?php echo apply_filters('atv_get_image_url', 'left-arrow?t=Icons'); ?>" id="archive-arrows"></span>
              <?php if(!isset($prevEpisodeURL)) : ?>
              <a href="<?= $baseUrlPath.'/'.$franchiseId; ?>">Back to Series</a>
              <?php else : ?>
              <a href="<?= $baseUrlPath.$prevEpisodeURL; ?>">Last Episode</a>
              <?php endif; ?>
          </span>
          <a href="<?= $baseUrlPath.'/'.$franchiseId?>" id="subnav-title"><span itemprop="partOfSeries"><?= $franchise->name; ?></span></a>, <span itemprop="partOfSeason"><?= $season->name; ?></span> : <span itemprop="name"><?= (strlen($episode->name) > 45 ) ? substr($episode->name,0,45).'...' : $episode->name; ?></span>   <!-- Next link -->
          <meta itemprop="image" content="<?php echo apply_filters('atv_get_image_url', $episode->image); ?>" />
          <meta itemprop="description" content="<?= $episode->longDescription; ?>" />
          <meta itemprop="episodeNumber" content="<?= $episode->episodeNumber; ?>" />
          <meta itemprop="endDate" content="<?= (isset($episode->endDate) && $episode->endDate != '') ? date('Y-m-d', $episode->endDate) : ''; ?>" />
          <meta itemprop="startDate" content="<?= (isset($episode->endDate) && $episode->endDate != '') ? date('Y-m-d', $episode->startDate) : ''; ?>" />
          <meta itemprop="timeRequired" content="<?= 'T'.str_replace(':','M',rljeApiWP_convertSecondsToMinSecs($episode->length)).'S'; ?>"/>

          <span class="subnav-next hidden-xs hidden-sm <?php if (!isset($nextEpisodeURL)) { echo 'invisible'; } ?>">
            <?php if (isset($nextEpisodeURL)): ?>
            <a href="<?= $baseUrlPath.$nextEpisodeURL; ?>">Next Episode </a><span> <img src="<?php echo apply_filters('atv_get_image_url', 'right-arrow?t=Icons'); ?>" id="archive-arrows"/></span>
            <?php endif; ?>
          </span>
        </h4>
        <!-- Brightcove Episode Player -->
        <div class="outer-container episode-player">
        <?php
        if ($isUserActive) :
        ?>
            <span itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
                <meta itemprop="thumbnailUrl" content="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=750'); ?>" />
                <meta itemprop="description" content="<?= $episode->longDescription; ?>" />
                <meta itemprop="name" content="<?= $episode->name; ?>" />
                <meta itemprop="uploadDate" content="<?= (isset($episode->endDate) && $episode->endDate != '') ? date('Y-m-d', $episode->startDate) : ''; ?>" />
                <meta itemprop="duration" content="<?= 'T'.  str_replace(':', 'M', rljeApiWP_convertSecondsToMinSecs($episode->length)).'S'; ?>" />
                <video
                    id="brightcove-episode-player"
                    data-account="3047407010001"
                    data-player="NJxe2G4ox"
                    data-embed="default"
                    data-video-id="ref:<?= $episode->id?>"
                    poster="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=750'); ?>"
                    class="video-js embed-responsive embed-responsive-16by9"
                    controls=""></video>
                <script src="//players.brightcove.net/3047407010001/NJxe2G4ox_default/index.js"></script>
                <?php if($isVideoDebuggerOn): ?>
                <link href="https://solutions.brightcove.com/marguin/debugger/css/brightcove-player-debugger.css" rel="stylesheet">
                <script src="https://solutions.brightcove.com/marguin/debugger/js/brightcove-player-debugger.min.js"></script>
                <?php endif;?>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        episodePlayer(<?php echo '"'.$episode->id.'"'; echo (isset($streamPositions[$episode->id], $streamPositions[$episode->id]['Position'])) ? ', '.$streamPositions[$episode->id]['Position'] : '';?>);

                        videojs('brightcove-episode-player').ready(function(){
                            var player = this;

                            function geoErrorMessage() {

                                videojs.log('RLJ:ATV - Error');
                                try {
                                  var errorMessage = '';
                                  if (typeof player.error().code != 'undefined') {
                                   errorMessage += player.error().code;
                                  }
                                  if (typeof player.error().message != 'undefined') {
                                    errorMessage += ' - ' + player.error().message;
                                  }
                                  if (typeof player.error().type != 'undefined') {
                                    errorMessage += ' - ' + player.error().type;
                                  }
                                  dataLayer.push({'event': '' + errorMessage});
                                  videojs.log('RLJ:ATV - Error: ' + errorMessage);
                                } catch (err) {
                                  console.log('Raised error: ' + err);
                                }

                                if(typeof player.catalog.error !== 'undefined' && player.catalog.error.data[0].error_subcode === 'CLIENT_GEO') {
                                  var message = 'Content is not available in your current location';
                                  console.log(message);
                                  player.errors({
                                    "errors": {
                                      "-1": {
                                          "headline": player.catalog.error.data[0].message,
                                          "type": player.catalog.error.data[0].error_subcode,
                                          "message": message
                                      }
                                    }
                                  });
                              }
                            }

                            player.on("error", geoErrorMessage);

                            player.on("ended", function() {
                                // hack for BC issue
                                if (player.autoplay()) {
                                    player.autoplay(false);
                                }
                                if (player.tech_ && player.tech_.hls) {
                                    player.tech_.hls.mediaSource.videoBuffer_.remove(0, Infinity);
                                }
                            });
                            <?php if($isVideoDebuggerOn): ?>
                            var options = {"debugAds":false, "logClasses":true, "showProgress":true, "useLineNums":true, "verbose":true};
                            player.playerDebugger(options);
                            <?php endif; ?>
                        });
                    });
                </script>
                <?php if(isset($streamPositions[$episode->id])): ?>
                <div class="continueWatching">
                    <div class="playerButtons">
                        <button class="continueEpisodeBtn js-player-start" data-no-scroll>
                            <span>PLAY FROM START</span>
                            <i class="fa fa-refresh" aria-hidden="true"></i>
                        </button>
                        <button class="continueEpisodeBtn js-player-resume" data-no-scroll>
                            <span>RESUME</span>
                            <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <?php endif;?>
                <div class="playNextEpisode">
                    <?php if(isset($nextEpisodeData)): ?>
                    <button class="js-play-next" data-next="<?= $baseUrlPath.$nextEpisodeURL; ?>">
                        <span>PLAY NEXT EPISODE</span>
                        <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                    </button>
                    <?php elseif(is_array($watchlist) && 0 < count($watchlist)): ?>
                    <button class="js-go-to" data-url="<?= $baseUrlPath.'/browse/yourwatchlist'; ?>">
                        <span>MY WATCHLIST</span>
                    </button>
                    <?php else: ?>
                    <button class="js-go-to" data-url="<?= $baseUrlPath; ?>">
                        <span>HOME</span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php if(isset($nextEpisodeData)): ?>
                <div id="nextEpisodeOverlay" class="episode">
                    <a class="js-play-start js-episode-loading" href="<?php echo $nextEpisodeURL; ?>">
                      <p class="headerText">Play Next Episode</p>
                      <div class="episodeOverlay">
                          <div class="imgOverlayContainer">
                            <span class="loading">
                                <i class="fa fa-refresh fa-spin fa-3x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </span>
                            <img src="https://qa-api.rlje.net/acorn/artwork/size/play-icon?t=Icons" id="play-episodes">
                            <img class="episodeImgOverlay" src="<?php echo apply_filters('atv_get_image_url', $nextEpisodeData->image.'?w=250'); ?>" alt="<?php echo $nextEpisodeData->name; ?>"/>
                          </div>
                          <div class="episodeDetailOverlay">
                              <p><?php echo $nextEpisodeData->name; ?></p>
                              <p><?php echo $nextEpisodeData->seasonName; ?>: <?php echo 'Episode '.$nextEpisodeData->episodeNumber; ?></p>
                          </div>
                      </div>
                    </a>
                </div>
                <?php endif; ?>
        <?php
        else :
            if(isset($franchise->episodes[0]->id)&& is_numeric($franchise->episodes[0]->id)) :
                $trailerId = $franchise->episodes[0]->id;
        ?>
            <span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
                <meta itemprop="thumbnailUrl" content="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=750'); ?>" />
                <meta itemprop="description" content="<?= $episode->longDescription; ?>" />
                <meta itemprop="name" content="<?= $episode->name; ?>" />
                <meta itemprop="uploadDate" content="<?= (isset($episode->startDate) && $episode->startDate != '') ? date('Y-m-d', $episode->startDate) : ''; ?>" />
                <div class="video" data-embedcode="<iframe style='border:none;z-index:4' src='//players.brightcove.net/3392051363001/2f9624d6-0dd2-46ff-9843-dadffb653bc3_default/index.html?videoId=<?= $trailerId; ?>'
                    allowfullscreen
                    webkitallowfullscreen
                    mozallowfullscreen></iframe>">
                    <img title="image title" alt="thumb image" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=750'); ?>"/>
                    <div class="acorntv-slogan">
                        <h3>Watch world-class TV from Britain and beyond</h3>
                        <h4>Always available, always commercial free</h4>
                        <a class="free-month" href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html">Start Your Free Trial</a>
                        <h5>
                            <button class="transparent js-play">
                                <img src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>" height="35" style="opacity:1">
                                <span>WATCH TRAILER</span>
                            </button>
                        </h5>
                    </div>
                    <script>
                    (function() {

                      var videos = document.querySelectorAll('.video');

                      for (var i = 0; i < videos.length; i++) {

                        // Closure to call the playVideo function.
                        if(videos[i].querySelector('.js-play')) {
                            videos[i].querySelector('.js-play').onclick = (function(index) {
                              return function() {
                                loadVideo(this, videos[index]);
                              };
                            })(i);
                        }
                      }

                      function loadVideo(button, video) {
                        var embedcode = video.dataset.embedcode;
                        var hasvideo = video.dataset.hasvideo;

                        // Only append the video if it isn't yet appended.
                        if (!hasvideo) {
                          video.insertAdjacentHTML('afterbegin', embedcode);
                          video.dataset.hasvideo = "true";
                          button.remove();
                        }
                      }

                    })();
                    </script>
                </div>
            </span>
        <?php
            else:
        ?>
            <img title="Play trailer" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=750'); ?>"/>
            <div class="acorntv-slogan">
                <h3>Watch world-class TV from Britain and beyond</h3>
                <h4>Always available, always commercial free</h4>
                <a class="free-month" href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html">Start Your Free Trial</a>
            </div>
        <?php
            endif;
        endif;
        ?>
        </div>
      </div>
    </div>
</div>

<!-- Episode content begins (descriptions, tags, more episodes, and related titles) -->
<div class="container episode">
    <div class="row">
      <!-- Episode Title and Description -->
      <div class="col-md-<?= ($isCast) ? '6' : '12'; ?>" id="eps-desc">
        <h4 class="subnav2">Description</h4>
        <p><?= $episode->name; ?>: <?= $episode->longDescription; ?></p>
      </div>

      <?php if($isCast) :?>
      <!-- Related Themes (wp tags) -->
      <div class="col-md-6 col-sm-12 column " id="eps-tags">
        <h4 class="subnav2">Cast</h4>
        <div class="episode-starring">
            <span>Starring: </span>
            <?php foreach($episode->actors as $key=>$value) : ?>
            <span itemprop="actor" itemscope itemtype="http://schema.org/Person">
                <span itemprop="name"><?= ($key+1<count($episode->actors)) ? $value.', ' : $value.'.'; ?> </span>
            </span>
            <?php endforeach;?>
        </div>
      </div>
      <?php endif;?>
    </div>

    <?php
        if(isset($streamPositions)):
            set_query_var('continueWatchingItems', $franchise->seasons);
            set_query_var('episodeID', $episode->id);
            set_query_var('totalEpisodes', $totalEpisodes);
            set_query_var('streamposition', $streamPositions);
            get_template_part('partials/continue-watching-carousel');
        else:
    ?>
    <!-- More Episodes Carousel -->
    <!-- Multiple carousels. If there is more than four episodes use bootstrap carousel-->

    <div class="col-md-12">
        <h4 class="subnav2" >More Episodes</h4>
        <?php
            set_query_var('franchiseName', $franchise->name);
            set_query_var('streamPositions', $streamPositions);

            $isLessThan4Episodes = apply_filters('atv_is_less_than_4_episodes', $franchise->seasons);

            if($isLessThan4Episodes) {
                set_query_var('season', $franchise->seasons[0]);
                set_query_var('ignoreSeasonHeader', true);
                get_template_part('partials/list-episode-items');
            }
            else {
                set_query_var('current_episode_id', $episode->id);
                set_query_var('seasons_carousel', $franchise->seasons);
                get_template_part('partials/more-episodes-carousel');
            }
        ?>
    </div>

    <!-- Multiple carousels. If there is less than four episodes display basic column grid-->
    <?php endif; ?>


    <!-- You May Also Like Carousel -->
    <?php
        set_query_var('also_watched_items', rljeApiWP_getViewersAlsoWatched($franchiseId));
        get_template_part('partials/viewers-also-watched');
    ?>
</div>
<?php
        get_footer();
    else :
        $haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'franchise');
        if($haveFranchisesAvailable) {
            require_once(get_404_template());
        }
        else {
            get_template_part('templates/franchisesUnavailable');
        }
    endif;
else :
    get_header();
    get_template_part('partials/plugin-deactivated-message');
    get_footer();
endif;
