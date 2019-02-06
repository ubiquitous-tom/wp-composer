<?php
$allcarousel = rljeApiWP_getHomeItems('carousel');
$dataCarousel = (isset($allcarousel->media) && is_array($allcarousel->media)) ? $allcarousel->media : array();

if(0 < count($dataCarousel)):
?>
    <div class="carousel feature-slide slide carousel-fade" id="heroCarousel">
        <div class="carousel-inner">
            <?php
                $i=0;
                $totalItems = count($dataCarousel);
                foreach ($dataCarousel as $item) :
                    $prevItem = ($i>0) ? $dataCarousel[$i-1] : $dataCarousel[$totalItems-1];
                    $nextItem = ($i<$totalItems-1) ? $dataCarousel[$i+1] : $dataCarousel[0];

                    // Gets Links
                    $prevLink = apply_filters('atv_heroCarusel_link', $prevItem);
                    $nextLink = apply_filters('atv_heroCarusel_link', $nextItem);
                    $currLink = apply_filters('atv_heroCarusel_link', $item);

                    // Images
                    $currImg = (!empty($item->image)) ? $item->image : '';
                    $prevImg = (!empty($prevItem->image)) ? $prevItem->image : '';
                    $nextImg = (!empty($nextItem->image)) ? $nextItem->image : '';

                    $i++;
            ?>
            <div class="item <?php echo ($item === reset($dataCarousel)) ? 'active' : ''; ?>">
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 left">
                    <a href="<?= $prevLink; ?>">
                        <img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($prevImg.'?t=Mobile'); ?>" style="width:100%; height:auto; ">
                        <img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($prevImg.'?t=Web3'); ?>" style="width:100%; height:auto; ">
                    </a>
                </div>
                <div class="container">
                    <a href="<?= $currLink; ?>">
                        <img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($currImg.'?t=Mobile'); ?>" style="width:100%; height:auto; ">
                        <img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($currImg.'?t=Web3'); ?>" style="width:100%; height:auto; ">
                    </a>
                </div>
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 right">
                    <a href="<?= $nextLink; ?>">
                      <img title="" alt="hero image" class="hero-img visible-xs visible-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($nextImg.'?t=Mobile'); ?>" style="width:100%; height:auto; ">
                      <img title="" alt="hero image" class="hero-img hidden-xs hidden-sm" src="<?php echo rljeApiWP_getImageUrlFromServices($nextImg.'?t=Web3'); ?>" style="width:100%; height:auto; ">
                    </a>
                </div>
            </div>
            <?php
                endforeach;
            ?>
        </div>
        <div class="container">
            <div class="control-position">
                <a class="left carousel-control" href="#heroCarousel" data-slide="prev">
                    <img class="hero-left-arrow" src="<?php echo rljeApiWP_getImageUrlFromServices('hero-left?t=Icons')?>"/>
                </a>
            </div>

            <div class="control-position">
                <a class="right carousel-control" href="#heroCarousel" data-slide="next">
                    <div>
                        <img class="hero-right-arrow" src="<?php echo rljeApiWP_getImageUrlFromServices('hero-right?t=Icons')?>"/>
                    </div>
                </a>
            </div>

            <ol class="carousel-indicators">
                <?php for($i=0;$i<count($dataCarousel); $i++): ?>
                <li data-target="#heroCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i === 0) ? 'active' : ''; ?>"></li>
                <?php endfor; ?>
            </ol>
        </div>
    </div>
<?php else: ?>
<div class="container">
    <div class="row">
        <div class="alert alert-info text-center">No results ...</div><br>
    </div>
</div>
<?php endif; ?>

