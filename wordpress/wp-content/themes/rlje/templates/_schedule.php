<?php 
get_header();
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$activeSection = get_query_var('section');
$listSections = array(
    'featured' => 'Recently Added',
    'comingsoon' => 'Coming Soon',
    'leavingsoon' => 'Leaving Soon'
);
?>
<div class="container schedule">
    <ul class="subnav">
        <span id="page-subhead" style="padding-bottom:24px;">FILTER BY:</span>
        <?php foreach ($listSections as $sectionKey=>$sectionName): ?>
        <li style="padding-right:25px;" <?php if($sectionKey === $activeSection):?>class="active"<?php endif; ?>><a href="<?= $baseUrlPath.'/schedule/'; echo ($sectionKey!='featured') ?  $sectionKey : ''; ?>"><?= $sectionName; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php
    $haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'section');
    if($haveFranchisesAvailable) :
        $aScheduleItems = rljeApiWP_getScheduleItems($activeSection);
        if (isset($aScheduleItems) && count($aScheduleItems) > 0) :
            $totalFranchisesResult = count($aScheduleItems);
            foreach ($aScheduleItems as $key=>$item) :
                $img = 'https://api.rlje.net/acorn/artwork/size/'.$item->image.'?w=750';
                if ($key%2==0) :
    ?>
    <div class="row">
    <?php endif; ?>
        <div class="col-md-6" itemprop="containsSeason" itemscope itemtype="http://schema.org/TVSeries">
            <?php if(isset($item->trailerId)&& is_numeric($item->trailerId)):?>
            <span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
                <meta itemprop="thumbnailUrl" content="<?= $img; ?>" />
                <meta itemprop="description" content="<?= $item->longDescription; ?>" />
                <meta itemprop="name" content="<?= $item->name; ?>" />
                <meta itemprop="uploadDate" content="<?= (isset($item->startDate) && $item->startDate != '') ? date('Y-m-d', $item->startDate) : ''; ?>" />
            </span>
            <div class="video" data-embedcode="<iframe style='border:none;z-index:4' src='//players.brightcove.net/3392051363001/2f9624d6-0dd2-46ff-9843-dadffb653bc3_default/index.html?videoId=<?= $item->trailerId; ?>'
                allowfullscreen 
                webkitallowfullscreen 
                mozallowfullscreen></iframe>">
                <img title="<?= $item->name;?>" alt="thumb image" class="wp-post-image" src="<?= $img; ?>" style="width:100%; height:auto;z-index:1;opacity:.75">
                <button class="transparent js-play"><img height="35" src="https://api.rlje.net/acorn/artwork/size/play-icon?t=Icons" style="opacity:1"><span>Watch Trailer</span></button>
            </div>
            <?php else: ?>
            <div class="video">
                <img title="<?= $item->name;?>" alt="thumb image" itemprop="image" class="wp-post-image" src="<?= $img; ?>" style="width:100%; height:auto;z-index:1;opacity:.75">
            </div>
            <?php endif;?>
            <div class="franchise-eps-bg"  style="margin-bottom:25px">
                <h5 itemprop="name"><?= $item->name;?></h5>
                <p itemprop="description"><?= $item->longDescription; ?></p>
            </div>
        </div>
    <?php if (($key+1)%2==0 || $key==$totalFranchisesResult-1) :?>
    </div>
    <?php endif; ?>
    <?php
            endforeach;
        else: 
            set_query_var('no_result_inline', true);
            get_template_part('partials/no-result-message');
        endif;
    else :
         get_template_part('partials/franchises-unavailable-message');
    endif;
    ?>
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

<?php 
get_footer(); 