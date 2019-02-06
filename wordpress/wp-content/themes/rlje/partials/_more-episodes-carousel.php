<?php
global $wp_query;

if (isset($wp_query->query_vars['streamPositions'])) {
    $streamPositions = $wp_query->query_vars['streamPositions'];
}

$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$franchiseName = (isset($wp_query->query_vars['franchiseName'])) ? $wp_query->query_vars['franchiseName'] : null;
$franchiseId = $wp_query->query_vars['franchise_id'];
$currentEpisodeNumber = $wp_query->query_vars['current_episode_id'];
$seasons = $wp_query->query_vars['seasons_carousel'];
?>
<div class="carousel carousel-respond-slide slide" id="popularseries" data-interval="false">
    <div class="row">
        <div class="carousel-inner">
        <?php
            foreach($seasons as $seasonkey => $season) :
        ?>
            <?php
                foreach($season->episodes as $key => $episode) :
                    $episodeURL =  $baseUrlPath.'/'.$franchiseId.'/'.  rljeApiWP_convertSeasonNameToURL($season->name).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($episode->name);
                    $isActive = ($episode->id === $currentEpisodeNumber) ? true : false;
                    $episodeNumber = apply_filters('atv_get_episode_number', $episode, ($key+1));
            ?>
            <div class="item <?php if($isActive) echo 'active'; ?>" itemprop="episode" itemscope itemtype="http://schema.org/TVEpisode">
                <a itemprop="url" href="<?= $episodeURL; ?>">
                    <div class="col-xs-12 col-sm-6 col-md-3"> 
                        <img src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>" id="play-episodes">
                        <img itemprop="image" style="margin-top:10px;" width="100%" src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=500'); ?>"/>
                        <?php if(isset($streamPositions, $streamPositions[$episode->id])) : $streamPositionData = $streamPositions[$episode->id]; ?>
                        <div class="progress progress-danger">
                            <div class="bar" style="width: <?= $progress = (($streamPositionData["Position"] / $streamPositionData["EpisodeLength"] ) * 100); ?>%;">
                                <span class="watched"><?= rljeApiWP_convertSecondsToMinSecs($streamPositionData["Position"]); ?></span>
                            </div>
                            <span class="length"><?= rljeApiWP_convertSecondsToMinSecs($streamPositionData["EpisodeLength"]); ?></span>
                        </div>
                        <?php endif; ?>
                        <meta itemprop="timeRequired" content="<?= 'T'.str_replace(':','M',rljeApiWP_convertSecondsToMinSecs($episode->length)).'S'; ?>"/>
                        <meta itemprop="partOfSeries" content="<?= $franchiseName ?>" />
                        <meta itemprop="partOfSeason" content="<?= $season->name ?>" />
                        <div>
                            <div class="franchise-eps-bg">
                                <h5 itemprop="name"><?php echo $episode->name; ?></h5>
                                <h6><?php echo $season->name; ?>: Episode <span itemprop="episodeNumber"><?php echo $episodeNumber; ?></span></h6>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php
                endforeach;
            ?>
        <?php 
            endforeach;
        ?>
        </div>
    </div>
    <a class="left carousel-control" href="#popularseries"  id="carousel-arrow"  data-slide="prev"><img class="carousel-img"  src="<?= get_template_directory_uri(); ?>/img/arrowleft.png"/></a>
    <a class="right carousel-control" href="#popularseries" data-slide="next"   id="carousel-arrow" ><img class="carousel-img" src="<?= get_template_directory_uri(); ?>/img/arrowright.png"/></a> 
</div>