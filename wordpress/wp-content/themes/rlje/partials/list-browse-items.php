<?php
global $wp_query;
$listItems = $wp_query->query_vars['array_list_items'];
$baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
$totalListItems = count($listItems);
if ($totalListItems > 0) :
    $itemCount = 0;
    foreach ($listItems as $key => $item) :
        if(!isset($item->id)) {
          $item->id = $item->franchiseID;
        }
        if(!isset($item->id)) {
            continue;
        }
        if(!isset($item->name)) {
          $item->name = (isset($item->id)) ? $item->id: $item->franchiseID;
        }
        if(!isset($item->image)) {
          $item->image = $item->id.'_avatar';
        }
        $is_newRow = ($key%4 == 0) ? true : false;
        $itemCount++;
        $dataAZ = preg_replace("/^The\s(.+)/i", '$1', strtolower($item->name));
        if($is_newRow) :
            $itemCount = 0;
?>
<div class="row">
<?php endif;?>
    <div class="col-sm-6 col-md-6 col-lg-3" itemscope itemtype="http://schema.org/TVSeries" data-az="<?= $dataAZ; ?>" data-added="<?= $key+1; ?>">
        <a itemprop="url" href="<?= $baseUrlPath.'/'.$item->id; ?>/">
          <img title="<?php echo $item->name; ?>" alt="<?php echo $item->id; ?>" class="wp-post-image" itemprop="image" src="<?php echo rljeApiWP_getImageUrlFromServices($item->image.'?w=550'); ?>" style="width:100%; height:auto;" />
        </a>
        <p itemprop="name" class="franchise-title"><?= $item->name; ?></p>
    </div>
<?php if($itemCount === 3 || ($key === $totalListItems - 1)) :?>
</div>
<?php endif;
    endforeach;
 else: 
?>
<div class="row">
    <?php 
        if($wp_query->query_vars['section'] === 'yourwatchlist') {
            $wp_query->query_vars['no_result_message'] = 'Click the "Add to Watchlist" button to add your favorite shows to your watchlist. <br/>You\'ll be able to access your watchlist from any device.<br/>';
        }
        $wp_query->query_vars['no_result_inline'] = true;
        get_template_part('partials/no-result-message'); 
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            jQuery('li.item').css('cursor','default');
        });
    </script>
</div>
<?php
endif;