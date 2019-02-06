<?php
$brightcove_settings   = get_option( 'rlje_theme_brightcove_shared_settings' );
$brightcove_account_id = $brightcove_settings['shared_account_id'];
$brightcove_player_id  = $brightcove_settings['shared_player_id'];
$about_vide_id         = '5180867444001';
get_header();
?>
<div id="watch-options">
	<section class="intro">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
					<p class="embed-responsive embed-responsive-16by9">
						<video
							id="umc-about"
							data-video-id="<?php echo $about_vide_id; ?>"
							data-account="<?php echo $brightcove_account_id; ?>"
							data-player="<?php echo $brightcove_player_id; ?>"
							data-embed="default"
							class="vide-js embed-responsive-item"
							controls
						></video>
					</p>
				</div>
			</div>
		</div>
	</section>
	<section class="selection">
		<ul>
			<li class="watch-tv active">
				<a href="#watch-tv" data-toggle="tab">
					<i class="fa fa-tv"></i>
					<span>Watch on TV</span>
				</a>
			</li>
			<li class="watch-mobile">
				<a href="#watch-mobile" data-toggle="tab">
					<i class="fa fa-mobile"></i>
					<span>Watch on Mobile</span>
				</a>
			</li>
			<li class="watch-online">
				<a href="#watch-web" data-toggle="tab">
					<i class="fa fa-laptop"></i>
					<span>Watch Online</span>
				</a>
			</li>
			<li class="watch-prime">
				<a href="#watch-amazon" data-toggle="tab">
					<img src="<?php echo get_template_directory_uri() . '/img/prime_nav_2.png'; ?>" alt="Prime Video">
				</a>
			</li>
			<li class="watch-xfinity">
				<a href="#watch-xfinity" data-toggle="tab">
					<img src="<?php echo get_template_directory_uri() . '/img/xfinity_logo.png'; ?>" alt="Xfinity">
				</a>
			</li>
		</ul>
	</section>
	<section class="choices tab-content container">
		<div id="watch-tv" class="tab-pane active">
			<div class="appletv row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/apple_tv.png'; ?>" alt="Apple TV logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Apple TV</h5>
					<p>Visit the App Store to download <?php echo get_bloginfo( 'name' ); ?> to your new Apple TV.
					<br><small>Watching on a legacy Apple TV? Use AirPlay to stream to your TV.</small></p>
					<p class="device-link">
						<a href="https://apple.com/tv/">Learn more at Apple</a>
					</p>
				</div>
			</div>
			<div class="roku row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/roku_new.png'; ?>" alt="Roku logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Roku</h5>
					<p>Visit Roku's channel store and search for <strong><?php echo get_bloginfo( 'name' ); ?></strong> to add the channel.</p>
					<p class="device-link">
						<a href="https://channelstore.roku.com/details/54771/umc-urban-movie-channel">Learn more at Roku</a>
					</p>
				</div>
			</div>
			<div class="fire-tv row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/amazon_logo.png'; ?>" alt="Availabe on Amazon logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Amazon Fire TV</h5>
					<p>Visit Amazon's App store to download <?php echo get_bloginfo( 'name' ); ?> to your Amazon Fire Device</p>
					<p class="device-link">
						<a href="https://www.amazon.com/RLJ-Entertainment-Urban-Movie-Channel/dp/B016APTPSQ/">Learn more at At Amazon</a>
					</p>
				</div>
			</div>
			<div class="chromecast row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/chromecast_logo.png'; ?>" alt="Chromecast logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Chromecast</h5>
					<p>To stream via Chromecast, simply click the Chromecast icon located in the top right corner of the iOS and Android apps.</p>
					<p class="device-link">
						<a href="https://www.google.com/intl/en_us/chromecast/built-in/">Learn More at Google</a>
					</p>
				</div>
			</div>
		</div>
		<div id="watch-mobile" class="tab-pane">
			<div class="ios row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/app_store_badge.png'; ?>" alt="App store badge">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>iPhone &amp; iPad App</h5>
					<p>Visit the App Store to download <?php echo get_bloginfo( 'name' ); ?> on your iPad, iPhone or iPod Touch.</p>
					<p class="device-link">
						<a href="https://itunes.apple.com/us/app/umc-best-in-black-film-tv/id1032488115">Learn More at App Store</a>
					</p>
				</div>
			</div>
			<div class="android row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/googleplay_logo.png'; ?>" alt="Google Play Store logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Android App</h5>
					<p>Visit the Google Play Store to download <?php echo get_bloginfo( 'name' ); ?> on your Android devices.</p>
					<p class="device-link">
						<a href="https://play.google.com/store/apps/details?id=com.rljentertainment.umc.android">Learn More at Google Play</a>
					</p>
				</div>
			</div>
		</div>
		<div id="watch-web" class="tab-pane">
			<div class="online row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/browsers.png'; ?>" alt="Browsers logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Watch Online</h5>
					<p>Watch <?php echo get_bloginfo( 'name' ); ?> on your computer or tablet via web browser</p>
					<p class="device-link">
						<a href="https://support.umc.tv/">Read FAQs</a>
					</p>
				</div>
			</div>
		</div>
		<div id="watch-amazon" class="tab-pane">
			<div class="amazon-channels row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/amazon_prime_channels.png'; ?>" alt="Prime Channels Logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Available on Amazon Channels</h5>
					<p>Prime members can subscribe to <?php echo get_bloginfo( 'name' ); ?> via Amazon</p>
					<p class="device-link">
						<a href="/faqs">Learn more</a> or <a href="https://www.amazon.com/gp/video/offers/signup/ref=atv_3p_umc_c_kzsumc_1_1?ie=UTF8&benefitId=umc&pf_rd_i=umc&pf_rd_m=ATVPDKIKX0DER&pf_rd_p=3534828442&pf_rd_r=MASBF0SWNEKTGQCMQMJ6&pf_rd_s=center-1&pf_rd_t=12806">Sign up on Amazon</a>
					</p>
				</div>
			</div>
		</div>
		<div id="watch-xfinity" class="tab-pane">
			<div class="xfinity row">
				<div class="col-xs-5 col-sm-5 col-md-3">
					<img class="device-logo center-block" src="<?php echo get_template_directory_uri() . '/img/xfinity_logo_black.jpg'; ?>" alt="Xfinity logo">
				</div>
				<div class="col-xs-7 col-sm-7 col-md-9">
					<h5>Xfinity</h5>
					<p><?php echo get_bloginfo( 'name' ); ?> is available as an add-on channel to Xfinity TV customers.</p>
					<p class="device-link">
						<a href="/faqs/">Learn more</a> or <a href="https://www.xfinity.com/learn/digital-cable-tv/svod/umc">Add via Xfinity TV</a>
					</p>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
