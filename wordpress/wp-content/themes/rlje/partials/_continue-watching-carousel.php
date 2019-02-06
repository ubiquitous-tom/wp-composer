<?php

$continueWatchingItems = get_query_var('continueWatchingItems');
$totalEpisodes = get_query_var('totalEpisodes');
$streamPositions = get_query_var('streamposition');
$franchiseId = get_query_var('franchise_id');
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$showingCarosel = ($totalEpisodes > 4);
$episodeID = get_query_var('episodeID');

if(!empty($continueWatchingItems) && count($continueWatchingItems) > 0) :
?>
<div id="continueWatching" class="col-md-12 episode">
    <h4 class="subnav2">CONTINUE WATCHING</h4>
    <?php if ($showingCarosel): ?>
    <div class="carousel carousel-respond-slide slide" id="newreleases" data-interval="false">
    <?php endif; ?>
        <div class="row">
            <?php if ($showingCarosel): ?>
            <div class="carousel-inner">
            <?php endif; ?>
                <?php
                    $highlightNextEpisode = false;
                    foreach ($continueWatchingItems as $seasonKey=>$season) :
                        foreach ($season->episodes as $key=>$episode) :
                            $showEpisodeHighlighted = false;
                            $showEpisodeActive = false;
                            $isResume = false;
                            $streamPositionData = array();
                            $episodeNumber = apply_filters('atv_get_episode_number', $episode, ($key+1));
                            if(!empty($episodeID) && $episode->id === $episodeID) {
                                $showEpisodeActive = true;
                                $showEpisodeHighlighted = true;
                            }
                            if($highlightNextEpisode) {
                                $showEpisodeHighlighted  = true;
                                $highlightNextEpisode = false;
                            }
                            if(isset($streamPositions, $streamPositions[$episode->id])) {
                                $streamPositionData = $streamPositions[$episode->id];
                                $isLastStreamed = (isset($streamPositionData['Counter']) && 1 === $streamPositionData['Counter']);
                                if($isLastStreamed || $showEpisodeHighlighted) {
                                    $showEpisodeActive = (!$showEpisodeActive) ? !$showEpisodeHighlighted : true;
                                    $showEpisodeHighlighted = true;
                                    if($streamPositionData["Position"] < ($streamPositionData["EpisodeLength"] - 60)) {
                                        $isResume = true;
                                    }
                                    else {
                                        $showEpisodeHighlighted = false;
                                        $highlightNextEpisode = true;
                                    }
                                }
                            }
                ?>
                <div class="item<?php echo ($showEpisodeActive)? ' active': '' ?>" itemprop="episode" itemscope itemtype="http://schema.org/TVEpisode">
                    <div class="col-sm-6 col-md-3<?php echo ($showEpisodeHighlighted) ? ' highlight-episode' : ''; ?> " style="padding-top:25px;">
                        <a itemprop="url" href="<?php echo esc_url( $baseUrlPath.'/'.$franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($season->name).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($episode->name) . '/' ); ?>">
                            <img src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>" id="play-episodes" />
                            <img itemprop="image" width="100%" src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=500'); ?>" />
                            <?php if(0 < count($streamPositionData)) : ?>
                            <div class="progress progress-danger">
                                <div class="bar" style="width: <?= $progress = (($streamPositionData["Position"] / $streamPositionData["EpisodeLength"] ) * 100); ?>%;">
                                    <span class="watched"><?= rljeApiWP_convertSecondsToMinSecs($streamPositionData["Position"]); ?></span>
                                </div>
                                <span class="length"><?= rljeApiWP_convertSecondsToMinSecs($streamPositionData["EpisodeLength"]); ?></span>
                            </div>
                            <?php endif; ?>
                            <meta itemprop="timeRequired" content="<?= (!empty($episode->length)) ? 'T'.str_replace(':','M',rljeApiWP_convertSecondsToMinSecs($episode->length)).'S' : ''; ?>" />
                            <div class="franchise-eps-bg<?php echo ($showEpisodeHighlighted) ? ' no-margin-bottom': ''?>">
                                <h5 itemprop="name"><?= $episode->name; ?></h5>
                                <h6><?= $season->name; ?>: Episode <span itemprop="episodeNumber"><?= $episodeNumber; ?></span></h6>
                            </div>
                            <?php if($showEpisodeHighlighted) : $playType = (!empty($episodeID))? 'player': 'play'; ?>
                            <div class="continueWatching">
                                <?php if($isResume):  ?>
                                <button class="continueEpisodeBtn js-<?= $playType; ?>-start">
                                    <span>PLAY FROM START</span>
                                    <i class="fa fa-refresh" aria-hidden="true"></i>
                                </button>
                                <button class="continueEpisodeBtn js-<?= $playType; ?>-resume">
                                    <span>RESUME</span>
                                    <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                                </button>
                                <?php else: ?>
                                <button class="js-<?= $playType; ?>-resume">
                                    <span>Play <?php echo $season->name.': Episode '.$episodeNumber; ?></span>
                                    <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                                </button>
                                <?php endif;?>
                            </div>
                            <?php endif;?>
                        </a>
                    </div>
                </div>
                <?php
                        endforeach;
                    endforeach;
                ?>
            <?php if ($showingCarosel): ?>
            </div>
            <a class="left carousel-control" href="#newreleases"  id="carousel-arrow"  data-slide="prev"><img class="carousel-img"  src="<?= get_template_directory_uri(); ?>/img/arrowleft.png"/></a>
            <a class="right carousel-control" href="#newreleases" data-slide="next"   id="carousel-arrow" ><img class="carousel-img" src="<?= get_template_directory_uri(); ?>/img/arrowright.png"/></a>
            <?php endif; ?>
        </div>
    <?php if ($showingCarosel): ?>
    </div>
    <?php endif; ?>
</div>
<?php
endif;
?>
