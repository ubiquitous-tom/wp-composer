<?php
 /**
  * The template for acorntv landing page.
  */

get_header(); ?>

<style>
/* BASE CSS */
button.free-month{margin-left: 0;}
h1{font-size: 2em;}
h2{font-size: 1.5em;}
h3{font-size: 1.17em;}
h4{font-size: 1.12em;}
h5{font-size: .83em;}
h6{font-size: .75em;}

/* LANDING PAGE */
.hero { position: relative; margin-top: 45px; width: 100%; /* for IE 6 */}
#hero-content { position: absolute; padding: 4% 0; top:0; left: 0; max-width:415px;}
#hero-content.heroText{position: relative; max-width: none;}
.hero-header {color: rgb(255, 255, 255);font-family: source sans pro;font-size: 28px;letter-spacing: 0.03em;line-height: 33px;margin-top: 15px;text-transform: none;}
.hero-callout{color: rgb(255, 255, 255); font-family: oxygen; font-size: 15px; font-weight: 300; line-height: 25px;}
.landing-body{padding-top: 0px; padding-bottom: 40px; background-color: #fff; color: rgb(85, 85, 85);}
.landing-body h1,.landing-body h2,.landing-body h3,.landing-body h4,.landing-body h5{font-family: source sans pro;font-weight: 600;letter-spacing: 0.02em;margin-top:40px;line-height: 25px;text-transform:none;color:#333;}
.landing-body p{color: rgb(85, 85, 85); letter-spacing: 0.02em; font-family: oxygen; margin-bottom: 15px; margin-top: 15px; font-size: 13.5px; line-height: 25px;}
.landing-link,.landing-link:focus,.landing-link:hover,.landing-link:active{font-family: source sans pro;font-weight: 600;letter-spacing: 0.17em;text-transform: uppercase;font-size:12px;color:#333;border-bottom:1px solid}
.landing-or{font-size:oxygen;font-size:13px; margin-left:5px;margin-right:5px;color:#555;letter-spacing:.02em;line-height:30px;margin-bottom:35px;}
.quote-container{margin: 0px auto; text-align: center; background: rgb(250, 250, 250) none repeat scroll 0% 0%; padding: 25px 0px; border-top: 1px solid rgb(238, 238, 238); border-bottom: 1px solid rgb(238, 238, 238);}
.quote{color: rgb(85, 85, 85); text-align: center; font-weight: 200; letter-spacing: 0.04em; max-width: 450px; line-height: 35px; font-size: 17.5px; margin: 15px auto;}
.quote-contributor{color:#999; line-height: 40px; margin: 0px auto; text-align: center; font-weight: 200; font-size:13px; letter-spacing: 0.04em; max-width: 500px;margin-top:10px;}
.atv-callout{letter-spacing: 0.02em; font-family: oxygen; margin-bottom: 10px; margin-top: 40px; line-height: 25px; color: rgb(240, 240, 240); font-weight: 300; font-size: 13.5px;}
.quote-img{width:25px;opacity: .16}

@media all and (min-width: 1100px) {

}

@media (min-width: 768px){
button{font-size:12.5px;}
.landing-link,.landing-link:focus,.landing-link:hover,.landing-link:active{font-size:12.5px;}
.landing-body{padding-top: 35px; padding-bottom: 50px;}
.landing-body h1,.landing-body h2,.landing-body h3,.landing-body h4,.landing-body h5{margin-top:12px;}
.landing-body p{font-size: 14.5px; line-height: 30px;margin-top:8px}
.quote-container{padding: 45px 0px;}
.quote{font-size: 18.5px;}
.quote-contributor{font-size:14px;}
.quote-img{width:30px;}
.atv-callout{font-size: 15.1px;margin-top: 15px; line-height: 30px }
}
</style>
<?php
    while ( have_posts() ) :
        the_post();    
        $postID = get_the_ID();
        if(isset($_GET['preview_id'], $_GET['preview']) && $_GET['preview']) {
            $autoSave = wp_get_post_autosave($postID);
            $postID = $autoSave->ID;
        }
        $featuredImg = get_post_meta($postID, '_atv_featuredImageUrl', true);
        $franchiseId = get_post_meta($postID, '_atv_franchiseId', true);
        $quote_desc = get_post_meta($postID, '_atv_quote_desc', true);
        $quote_auth = get_post_meta($postID, '_atv_quote_auth', true);
        $trailerId = get_post_meta($postID, '_atv_trailer_id', true);
        $baseUrlPath = (function_exists('rljeApiWP_getBaseUrlPath')) ? rljeApiWP_getBaseUrlPath() : '';
        $franchiseLink = (!empty($franchiseId)) ? $baseUrlPath.'/'.$franchiseId : null;
        $environment = apply_filters('atv_get_extenal_subdomain', ''); //Leave empty value to production else set -dev or -qa.
?>

<div class="hero">
  <div style="background:#000">
    <div class="container">
        <?php 
            if(!empty($featuredImg)):
                $isFranchiseLink = (!empty($franchiseLink)) ? true: false;
                if($isFranchiseLink):
        ?>
        <a href="<?= $franchiseLink; ?>">
        <?php  endif;?>
            <img title="<?= get_the_title(); ?>" alt="thumb image" class="wp-post-image" src="<?= $featuredImg; ?>" style="width:100%; height:auto;">
        <?php if($isFranchiseLink): ?>
        </a>
        <?php endif;?>
        <div class="hidden-xs hidden-sm col-md-4 col-md-offset-8" id="hero-content">
        <?php else: ?>
        <div class="col-sm-12 col-md-6 col-md-offset-3 heroText" id="hero-content">
        <?php endif; ?>
          <img width="165" src="https://s3.amazonaws.com/acorntv-artwork-storage/atvlogo_v2_small.png">
          <h1 class="hero-header"><?php the_title(); ?> </h1>
          <p class="hero-callout"><?= get_the_excerpt(); ?></p>
          <a href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html" class="button-link">
            <button class="free-month">Start Free Month</button>
          </a>
        </div>
    </div>
  </div>
</div>

<section class="landing-body">
  <div class="container"> 
    <div class="col-md-7 col-sm-12 column ">
        <?php the_content(); ?>
        <a href="https://signup<?= $environment; ?>.acorn.tv/createaccount.html" class="landing-link">Start free month</a> 
        <?php if(!empty($franchiseLink)) : ?>
        <span class="landing-or">or </span> 
        <a href="<?= $franchiseLink; ?>" class="landing-link">View Episode</a>
        <?php endif; ?>
    </div>

    <?php if(!empty($trailerId)): ?>
    <div class="hidden-xs hidden-sm col-md-5 column">
        <div style="display: block; position: relative; max-width: 100%;"><div style="padding-top: 56.25%;"><video style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;" 
        data-video-id="<?= $trailerId; ?>" 
        data-account="3392051363001" 
        data-player="default" 
        data-embed="default" 
        class="video-js" 
        controls></video>
        <script src="//players.brightcove.net/3392051363001/default_default/index.min.js"></script></div></div>
    </div>
    <?php endif; ?>
  </div>
</section>

<section class="quote-container">
  <img class="quote-img" src="https://atv3.s3.amazonaws.com/landing/quote.svg" />
  <p class="quote"><?= $quote_desc; ?></p>
  <p class="quote-contributor" style=""><?= $quote_auth; ?></p>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
