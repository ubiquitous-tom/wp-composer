<?php 
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$reviews = get_option('acorntv_latest_news');
$reviewsSources = get_option('acorntv_news_options');
?>
<div class="col-md-12 col-sm-12 column col-lg-6 press">
    <?php 
    if(is_array($reviews) && count($reviews) > 1) :
        foreach($reviews as $item) : 
            $externalLink = (preg_match('/^http[s]*\:[\/]{2}/i', $item['link'])) ? $item['link'] : 'http://'.str_replace([':','//'],'',$item['link']);
    ?>
    <div class="press-border"> 
        <a href="<?= $externalLink; ?>" onclick="window.open('<?= $externalLink; ?>', 'newwindow', 'scrollbars=yes,top=200, left=100,width=850, height=600'); return false;" target="_blank">
            <div class="col-xs-4 col-sm-4 col-md-3">
                <?php if(!empty($item['image'])) :?>
                <img title="<?= $item['title']; ?>" alt="thumb image" class="wp-post-image" src="<?= $item['image']; ?>" style="width:100%; height:auto;opacity:.55;max-width:80px;">
                <?php endif;?>
            </div>

            <div class="col-xs-8 col-sm-8 col-md-9 press-title">
                <span><?= $item['title']; ?></span>
                <span class="fa fa-angle-right icon-sm"></span>
            </div>
        </a>
    </div>
    <?php 
        endforeach;
    endif;
    ?>
</div>