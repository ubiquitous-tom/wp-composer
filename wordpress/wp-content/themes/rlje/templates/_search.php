<?php 
$haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'search');
if($haveFranchisesAvailable) :
    get_header();
    $baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
    $searchText = urldecode($wp_query->query_vars['search_text']);
    $searchByFranchisesResult = null;
    $searchByEpisodesResult = null;
    $totalFranchisesResult = 0;
    $limitCharacter = array(
        'number' => 2,
        'text' => 'two'
    );

    if (function_exists('rljeApiWP_searchByFranchises')) {
        $haveMinCharacter = (strlen($searchText) < $limitCharacter['number']);
        if (!$haveMinCharacter || strtolower($searchText) === 'qi') { // Qi is a franchise's name.
            $searchByFranchisesResult = rljeApiWP_searchByFranchises($searchText);
            $searchByEpisodesResult = rljeApiWP_searchByEpisodes($searchText);
            $totalFranchisesResult=(isset($searchByFranchisesResult->franchises)) ? count($searchByFranchisesResult->franchises) : 0;
            $totalEpisodesResult=(isset($searchByEpisodesResult->episodes)) ? count($searchByEpisodesResult->episodes) : 0;
            $showFranchisesCarousel = (4 < $totalFranchisesResult);
            $showEpisodesCarousel = (4 < $totalEpisodesResult);
            if ($totalFranchisesResult > 0) :
?>
<section class="search">
    <div class="container">
        <h4 class="subnav">Franchise Results for <?= $searchText; ?></h4>
        <div class="<?php echo ($showFranchisesCarousel) ? ' hidden-lg' : ''; ?>">
            <?php 
                foreach ($searchByFranchisesResult->franchises as $key=>$franchise) : 
                    if ($key%4==0) :
            ?>
            <div class="row">
            <?php 
                    endif;
            ?>
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <a href="<?= $baseUrlPath.'/'.$franchise->id; ?>">
                        <img title="<?= $franchise->name; ?>" alt="thumb franchise image" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $franchise->image.'?w=550'); ?>" style="width:100%; height:auto; ">
                    </a> 
                </div>
            <?php
                    if (($key+1)%4==0 || $key==$totalFranchisesResult-1) :
            ?>
            </div>
            <?php 
                    endif;
                endforeach;
            ?>
        </div>
        <?php if($showFranchisesCarousel): ?>
        <div class="carousel carousel-block-slide slide visible-lg" id="<?php echo $section_key = 'franchises-results'; ?>" data-interval="false" data-wrap="false">
            <div class="row">
                <div class="carousel-inner">
                    <?php 
                        $franchisesItems = apply_filters('atv_get_completed_carousel_items', $searchByFranchisesResult->franchises);
                        $totalFranchisesResult = count($franchisesItems);
                        foreach ($franchisesItems as $key=>$franchise) : 
                            if ($key%4==0) :
                    ?>
                    <div class="item <?php echo ($key == 0) ? 'active' : ''; ?>">
                        <?php 
                            endif;
                        ?>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <a href="<?= $baseUrlPath.'/'.$franchise->id; ?>">
                                <img title="<?= $franchise->name; ?>" alt="thumb franchise image" class="wp-post-image" src="<?php echo apply_filters('atv_get_image_url', $franchise->image.'?w=550'); ?>" style="width:100%; height:auto; ">
                            </a>
                        </div>
                    <?php
                            if (($key+1)%4==0 || $key==$totalFranchisesResult-1) :
                    ?>
                    </div>
                    <?php 
                            endif;
                        endforeach;
                    ?>
                </div>
            </div>
            <a class="left carousel-control" href="#<?php echo $section_key; ?>" id="carousel-arrow" data-slide="prev"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-left?t=Icons"/></a>
            <a class="right carousel-control" href="#<?php echo $section_key; ?>" data-slide="next" id="carousel-arrow"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-right?t=Icons"/></a> 
        </div>
        <?php endif; ?>
    </div>
</section>
<?php
        endif;
        if ($totalEpisodesResult > 0) {
?>
<section class="search-episode">
    <div class="container">
        <h4 class="subnav">Episode Results for <?= $searchText; ?></h4>
        <div class="<?php echo ($showEpisodesCarousel) ? ' hidden-lg' : ''; ?>">
            <?php 
                foreach ($searchByEpisodesResult->episodes as $key=>$episode) : 
                    if(!empty($episode->image)) { //Workaround to get episode number
                        preg_match('/.+ep([\d]{2}).+/i', $episode->image, $episodeNumber); 
                    }
                    $episodeNumber = apply_filters('atv_get_episode_number', $episode, (int)$episodeNumber[1]);
                    if ($key%4==0):
            ?>
            <div class="row">
            <?php 
                    endif;
            ?>
                <a href="<?= $baseUrlPath.'/'.$episode->franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($episode->seriesName).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($episode->name)?>">
                    <div class="col-sm-6 col-md-3" style="margin-top:15px;">
                        <img id="play-episodes" src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>"/>
                        <img width="100%" title="<?= $episode->name; ?>" alt="thumb episode image"  src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=500'); ?>"/>
                        <div class="franchise-eps-bg">
                            <h4 class="text-center"><?= $episode->franchiseName;?></h4>
                            <h5><?= $episode->name;?></h5>
                            <h6><?= (isset($episode->seriesName)) ? $episode->seriesName : ''; echo (!empty($episodeNumber)) ? ': Episode '.$episodeNumber : ''; ?></h6>
                        </div>
                    </div>
                </a>
            <?php
                    if (($key+1)%4==0 || $key==$totalEpisodesResult-1) :
            ?>
            </div>
            <?php 
                    endif;
                endforeach;
            ?>
        </div>
        <?php if($showEpisodesCarousel): ?>
        <div class="carousel carousel-block-slide slide visible-lg" id="<?php echo $section_key = 'episodes-results'; ?>" data-interval="false" data-wrap="false">
            <div class="row">
                <div class="carousel-inner">
                    <?php 
                        $EpisodesItems = apply_filters('atv_get_completed_carousel_items', $searchByEpisodesResult->episodes);
                        $totalEpisodesResult = count($EpisodesItems);
                        foreach ($EpisodesItems as $key=>$episode) : 
                            if(!empty($episode->image)) { //Workaround to get episode number
                                preg_match('/.+ep([\d]{2}).+/i', $episode->image, $episodeNumber); 
                            }
                            $episodeNumber = apply_filters('atv_get_episode_number', $episode, (int)$episodeNumber[1]);
                            if ($key%4==0):
                    ?>
                    <div class="item <?php echo ($key == 0) ? 'active' : ''; ?>">
                    <?php 
                            endif;
                    ?>
                        <a href="<?= $baseUrlPath.'/'.$episode->franchiseId.'/'.rljeApiWP_convertSeasonNameToURL($episode->seriesName).'/'.rljeApiWP_convertEpisodeNameToURLFriendly($episode->name)?>">
                            <div class="col-xs-12 col-sm-6 col-md-3" style="margin-top:15px;">
                                <img id="play-episodes" src="<?php echo apply_filters('atv_get_image_url', 'play-icon?t=Icons'); ?>"/>
                                <img width="100%" title="<?= $episode->name; ?>" alt="thumb episode image"  src="<?php echo apply_filters('atv_get_image_url', $episode->image.'?w=500'); ?>"/>
                                <div class="franchise-eps-bg">
                                    <h4 class="text-center"><?= $episode->franchiseName;?></h4>
                                    <h5><?= $episode->name;?></h5>
                                    <h6><?= (isset($episode->seriesName)) ? $episode->seriesName : ''; echo (!empty($episodeNumber)) ? ': Episode '.$episodeNumber : ''; ?></h6>
                                </div>
                            </div>
                        </a>
                    <?php
                            if (($key+1)%4==0 || $key==$totalEpisodesResult-1) :
                    ?>
                    </div>
                    <?php 
                            endif;
                        endforeach;
                    ?>
                </div>
            </div>
            <a class="left carousel-control" href="#<?php echo $section_key; ?>" id="carousel-arrow" data-slide="prev"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-left?t=Icons"/></a>
            <a class="right carousel-control" href="#<?php echo $section_key; ?>" data-slide="next" id="carousel-arrow"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-right?t=Icons"/></a> 
        </div>
        <?php endif; ?>
    </div>
</section>
<?php
            }
            if ($totalEpisodesResult == 0 && $totalFranchisesResult == 0) {
                showMessage('Your search did not match any shows. Please try again.');
            }
        }
        else {
            showMessage('Please enter at least <b>'.$limitCharacter['text'].'</b> characters.');
        }
    }
    else {
        get_template_part('partials/plugin-deactivated-message');
    }
    get_footer();
else:
    get_template_part('templates/franchisesUnavailable');
endif;

function showMessage($message) {
?>
<section class="search">
    <div class="container">
        <div class="row">
            <h4 class="subnav">
                <?= $message; ?>
            </h4>
        </div>
    </div>
</section>
<?php
}
