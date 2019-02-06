<?php 
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$marketingPlaces = get_option('acorntv_marketing_placeholder');
?>
<div class="col-md-12 col-lg-6">
    <div class="home-hero-carousel">
        <div id="carousel_fade" class="carousel slide carousel-fade " style="margin-top:25px;" data-pause="true"  data-interval="false" data-ride="carousel">
            <div class="carousel-inner">
                <a class="left carousel-control" style="margin-left:0px;width:35px;top:42%" href="#carousel_fade" data-slide="prev" id="carousel-arrow"><img src="https://api.rlje.net/acorn/artwork/size/left-arrow?t=Icons" width="35px"></a>
                <a class="right carousel-control" href="#carousel_fade" data-slide="next" id="carousel-arrow" style="width:32px;top:42%"><img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" width="35px"></a>

                <?php 
                    foreach($marketingPlaces as $key=>$item) :
                        if(!empty($item['src'])) :
                ?>
                <div class="item <?= ($key==0) ? 'active': ''; ?>">
                    <?php if($item['type'] == 'image') :?>
                    <a <?php if(!empty($item['franchiseId'])) : ?>href="<?= $baseUrlPath.'/'.$item['franchiseId']; ?>" <?php endif; ?>>
                        <img alt="thumb marketing image" class="sliderimage" src="<?= $item['src']?>" style="height:auto;opacity:.9">
                    </a>
                    <?php elseif($item['type'] == 'extImage'): $externalLink = (preg_match('/^http[s]*\:[\/]{2}/i', $item['externalLink'])) ? $item['externalLink'] : 'http://'.str_replace([':','//'],'',$item['externalLink']); ?>
                    <a <?php if(!empty($item['externalLink'])) : ?>href="<?= $externalLink; ?>" <?php endif; ?> onclick="window.open('<?= $externalLink; ?>', 'newwindow', 'scrollbars=yes,top=200, left=100,width=850, height=600'); return false;" target="_blank">
                        <img alt="thumb marketing image" class="sliderimage" src="<?= $item['src']?>" style="height:auto;opacity:.9">
                    </a>
                    <?php else :?>
                    <div style="display: block; position: relative; max-width: 100%;">
                        <div style="padding-top: 50%;">
                            <iframe 
                                class="embed-responsive" 
                                style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;border:none;" 
                                src="//players.brightcove.net/3047407010001/r1ZjWi4Ab_default/index.html?videoId=<?= $item['src']?>" 
                                allowfullscreen="" 
                                webkitallowfullscreen="" 
                                mozallowfullscreen="">
                            </iframe>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php 
                        endif;
                    endforeach;
                ?>
            </div>
        </div>
    </div>
</div>
