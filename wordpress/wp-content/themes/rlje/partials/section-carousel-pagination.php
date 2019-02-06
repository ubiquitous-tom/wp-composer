<?php
$section = get_query_var('carousel-section');
$sectionTitle = $section['title'];
$categoryObj = $section['categoryObj'];
$categoryId = $categoryObj->id;
$is_showingViewAll = (isset($section['showViewAllLink'])) ? $section['showViewAllLink'] : true;
$browseId = apply_filters('atv_get_browse_section_id', $categoryId);
$contentPageId = apply_filters('atv_get_contentPage_section_id', $categoryId);

$carouselItems = (isset($categoryObj->media)) ? $categoryObj->media : null;
$totalPage = (isset($categoryObj->totalpages)) ? $categoryObj->totalpages : 0;

$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';

$isShowingArrows = true;

if(isset($carouselItems) && count($carouselItems) > 0) :
?>
<h4 <?= ($browseId === 'mystery') ? 'id="third-spotlight"' : ''; ?> class="subnav2"><?php echo $sectionTitle;?></h4>
<?php
    if($is_showingViewAll):
?>
<div class="view-all hidden-xs">
    <a href="<?= $baseUrlPath.'/browse/'.$browseId; ?>"> View all <span><img width="8" src="https://api.rlje.net/acorn/artwork/size/double-arrows-white?t=Icons"/></span></a>
</div>
<?php
    endif;
?>
<div class="carousel carousel-pagination-slide slide" id="<?php echo $browseId; ?>" data-interval="false" data-total-pages="<?php echo $totalPage; ?>" data-page-loaded="1" data-content="<?php echo $contentPageId; ?>">
    <div class="row">
      <div class="carousel-inner">
            <?php
            if(count($carouselItems) > 4) :
                foreach ($carouselItems as $key=>$item) :
            ?>
            <div class="item<?php echo (0 === $key) ? ' active' : '';?>" data-item="<?php echo $key; ?>">
                <?php
                    for($i=0,$j=0; $i<4; $i++):
                      if(0<$i) {
                        if(isset($carouselItems[$key+1])) {
                          $key++;
                          $item = $carouselItems[$key];
                        }
                        else {
                          $item = (isset($carouselItems[$j])) ? $carouselItems[$j] : new stdClass();
                          $j++;
                        }
                      }


                      // Set href id and image.
                      if(isset($item->id) || isset($item->franchiseID)) {
                        $item = apply_filters('atv_add_img_and_href', $item);
                      }
                      else {
                        // Next item.
                        break;
                      }

                ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3<?php echo (0<$i) ? ' cloneditem-'.$i : ''?>" id="avatar">
                  <a href="<?php echo $baseUrlPath.'/'.$item->href; ?>">
                    <img title="<?= $sectionTitle; ?>" alt="<?= $sectionTitle; ?> image" class="wp-post-image" id="avatar-rollover" src="<?php echo $item->img; ?>?w=400&h=225" style="width:100%; height:auto; " />
                  </a>
                </div>
                <?php endfor;?>
            </div>
            <?php endforeach;
            else:
                $isShowingArrows = false;
            ?>
            <div class="item active">
                <?php
                    foreach ($carouselItems as $key=>$item) :
                        $item = apply_filters('atv_add_img_and_href', $item);
                ?>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" id="avatar">
                  <a href="<?php echo $baseUrlPath.'/'.$item->href; ?>">
                    <img title="<?= $sectionTitle; ?>" alt="<?= $sectionTitle; ?> image" class="wp-post-image" id="avatar-rollover" src="<?php echo $item->img; ?>?w=400&h=225" style="width:100%; height:auto; " />
                  </a>
                </div>
                <?php endforeach;?>
            </div>
            <?php endif; ?>
      </div>
    </div>
    <?php if($isShowingArrows): ?>
    <a class="left carousel-control" href="#<?php echo $browseId; ?>" id="carousel-arrow" data-slide="prev"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-left?t=Icons"/></a>
    <a class="right carousel-control" href="#<?php echo $browseId; ?>" data-slide="next" id="carousel-arrow"><img class="carousel-img" src="https://api.rlje.net/acorn/artwork/size/carousel-right?t=Icons"/></a>
    <?php endif;?>
</div>
<?php
endif;
