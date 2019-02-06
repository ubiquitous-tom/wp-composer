<?php
global $wp_query;
$ignoreSeasonHeader = false;

if (isset($wp_query->query_vars['streamPositions'])) {
    $streamPositions = $wp_query->query_vars['streamPositions'];
}

if (isset($wp_query->query_vars['ignoreSeasonHeader'])) {
    $ignoreSeasonHeader = $wp_query->query_vars['ignoreSeasonHeader'];
}
$franchiseId = $wp_query->query_vars['franchiseId'];
$season = $wp_query->query_vars['season'];

$template = get_query_var('pagecustom');

$highlightTemplatesEnabled = array(
    'franchise' => true,
    'episode' => true
);

$count = 0;
$franchiseName = (isset($wp_query->query_vars['franchiseName'])) ? $wp_query->query_vars['franchiseName'] : null;
$franchiseTotal = count($season->episodes) - 1;
$franchiseId = (isset($wp_query->query_vars['franchise_id'])) ? '/'.$wp_query->query_vars['franchise_id'] : null;
$seasonNameUrl = (isset($franchiseId, $wp_query->query_vars['season_name'])) ? '/'.$wp_query->query_vars['season_name'] : '/'.rljeApiWP_convertSeasonNameToURL($season->name);
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$baseUrlPath.= $franchiseId.$seasonNameUrl.'/';
$isHighligthingEpisode = false;
$isLogged = isset($_COOKIE["ATVSessionCookie"]);
$isHighligthingEnabled = !empty($highlightTemplatesEnabled[$template]);
$isStreamPosition = !isset($streamPositions);
$isFirstSeasson = (isset($season->seasonNumber) && 1 == $season->seasonNumber);

if($isLogged && $isHighligthingEnabled && $isStreamPosition && $isFirstSeasson) {
  $isHighligthingEpisode = true;
}

?>
<span itemprop="containsSeason" itemscope itemtype="http://schema.org/TVSeason">
    <meta itemprop="name" content="<?= $season->name; ?>" />
    <meta itemprop="numberOfEpisodes" content="<?= count($season->episodes); ?>" />
    <meta itemprop="seasonNumber" content="<?= (isset($season->seasonNumber)) ? $season->seasonNumber : ''; ?>" />
    <meta itemprop="partOfSeries" content="<?= $franchiseName ?>"/>
<?php
if(!$ignoreSeasonHeader) :
?>
    <div class="row">
        <h4 class="subnav2"><?php echo $season->name; ?></h4>
    </div>
<?php
endif;

foreach ($season->episodes as $key => $episode) :
    $showEpisodeHighlighted = ($isHighligthingEpisode && 0 == $key);
    $is_newRow = ($key%4 == 0) ? true : false;
    $count++;
    $episodeNumber = apply_filters('atv_get_episode_number', $episode, ($key+1));
    if($is_newRow) :
        $count = 0;
?>
    <div class="row" style="margin-bottom:15px;">
<?php endif; ?>
        <span itemprop="episode" itemscope itemtype="http://schema.org/TVEpisode">
            <div class="col-sm-6 col-md-3<?php echo ($showEpisodeHighlighted) ? ' highlight-episode' : ''; ?>" style="padding-top:25px;">
                <a itemprop="url" href="<?php echo esc_url( $baseUrlPath.rljeApiWP_convertEpisodeNameToURLFriendly($episode->name) . '/' ); ?>">
                    <img src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>" id="play-episodes" />
                    <img itemprop="image" width="100%" src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=500'); ?>" />
                    <?php if(isset($streamPositions, $streamPositions[$episode->id])) : $streamPositionData = $streamPositions[$episode->id]; ?>
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
                    <?php if($showEpisodeHighlighted): ?>
                    <div class="continueWatching">
                        <button class="js-play-resume">
                            <span>Play <?php echo $season->name.': Episode '.$episodeNumber; ?></span>
                            <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php endif;?>
                </a>
            </div>
        </span>
<?php if ($count == 3 || ($key == $franchiseTotal)) : ?>
    </div>
<?php
      endif;
endforeach;
?>
</span>
