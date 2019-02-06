<?php
$brightcove_settings   = get_option( 'rlje_theme_brightcove_shared_settings' );
$brightcove_account_id = $brightcove_settings['shared_account_id'];
$brightcove_player_id  = $brightcove_settings['shared_player_id'];
$about_vide_id         = '5180867444001';
get_header();
?>
<section class="content">
	<header class="page-hero">
		<div class="container">
			<h1>About us</h1>
		</div>
	</header>
	<div class="page-body">
		<div class="container">
			<article class="col-sm-8 col-sm-offset-2">
					<div class="entry-content">
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
						<p>Welcome to UMC – Urban Movie Channel. YOUR movies anytime, anyplace, anyhow.</p>
						<p><strong>UMC</strong> – <strong>Urban Movie Channel</strong> was created by <strong>Robert L. Johnson</strong>, Chairman of RLJ Entertainment and founder of Black Entertainment Television (BET), UMC is an urban-focused subscription streaming service in North America and features quality urban content that showcases feature films, documentaries, original series, stand-up comedy, and other exclusive content for African American and urban audiences. New titles added weekly include live stand-up specials like Martin Lawrence Presents: 1st Amendment Stand Up and Comedy Underground Series, and performances featuring Academy Award® winner Jamie Foxx and comedic rock star Kevin Hart; dramas including Blackbird starring Academy Award® winning actress and comedian Mo’Nique, Isaiah Washington, and directed by Patrik-Ian Polk, and Playin’ For Love, starring and directed by Robert Townsend; documentaries including Bill Duke’s Dark Girls and I Ain’t Scared of You: A Tribute to Bernie Mac; action/thrillers including The Colony starring Laurence Fishburne; and stage play productions including What My Husband Doesn’t Know by David E. Talbert.</p>
					</div>
			</article>
		</div>
	</div>
</section>
<?php
get_footer();
