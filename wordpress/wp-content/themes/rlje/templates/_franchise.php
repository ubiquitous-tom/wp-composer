<?php
$baseUrlPath = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
if ( count( $_POST ) > 0 && isset( $_COOKIE['ATVSessionCookie'], $_POST['action'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) :

	switch ( $_POST['action'] ) {
		case 'add':
			rljeApiWP_addToWatchlist( $_POST['franchise'], $_COOKIE['ATVSessionCookie'] );
			break;
		case 'remove':
			rljeApiWP_removeFromWatchlist( $_POST['franchise'], $_COOKIE['ATVSessionCookie'] );
			break;
	}

else :

	if ( function_exists( 'rljeApiWP_getFranchiseById' ) ) :
		$franchiseId = get_query_var( 'franchise_id' );
		$franchise   = rljeApiWP_getFranchiseById( $franchiseId );

		if ( isset( $franchise->id ) ) :
			if ( isset( $_COOKIE['ATVSessionCookie'] ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) ) {
				$getStreamPositions = rljeApiWP_getStreamPositionsByFranchise( $franchiseId, $_COOKIE['ATVSessionCookie'] );
				if ( isset( $getStreamPositions->streamPositions ) ) {
					$streamPositions = [];
					$countPositions  = 1;
					foreach ( $getStreamPositions->streamPositions as $streamPosition ) {
						$streamPositions[ $streamPosition->EpisodeID ] = [
							'Position'      => $streamPosition->Position,
							'EpisodeLength' => $streamPosition->EpisodeLength,
							'Counter'       => $countPositions,
						];
						$countPositions++;
					}
				}
			}
			$totalEpisodes = 0;
			foreach ( $franchise->seasons as $seasonItem ) {
				$totalEpisodes += count( $seasonItem->episodes );
			}
			get_header();
			$haveFranchisesAvailable = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'franchise' );
			$franchiseURL            = $baseUrlPath . '/' . $franchise->id;

			if ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && is_numeric( $franchise->episodes[0]->id ) ) {
				$franchiseArtLink  = $franchiseURL . '/trailer';
				$franchiseArtTitle = 'Click to view trailer';
			}
			if ( isset( $streamPositions ) ) {
				$streamPositionKeys = array_keys( $streamPositions );
				$streamPositionData = array_values( $streamPositions );
				$break              = false;
				if ( $streamPositionData[0]['Position'] < ( $streamPositionData[0]['EpisodeLength'] - 60 ) ) {
					// Select the episode to continue watching when artwork is clicked.
					foreach ( $franchise->seasons as $season ) {
						foreach ( $season->episodes as $keyEpisode => $episode ) {
							if ( $episode->id === $streamPositionKeys[0] ) {
								$episodeNumber    = apply_filters( 'atv_get_episode_number', $episode, ( $keyEpisode + 1 ) );
								$franchiseArtLink = $franchiseURL . '/' . rljeApiWP_convertSeasonNameToURL( $season->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $episode->name );

								$franchiseArtTitle = 'Click to continue watching ' . $season->name . ': Episode ' . $episodeNumber;
								$break             = true;
								break;
							}
						}
						if ( $break ) {
							break;
						}
					}
				} else {
					// Select the next episode to watch when artwork is clicked.
					$isNextEpisode = false;
					foreach ( $franchise->seasons as $season ) {
						foreach ( $season->episodes as $keyEpisode => $episode ) {
							$episodeNumber          = apply_filters( 'atv_get_episode_number', $episode, ( $keyEpisode + 1 ) );
							$isSetPositionAndLength = isset( $streamPositions[ $episode->id ], $streamPositions[ $episode->id ]['Position'], $streamPositions[ $episode->id ]['EpisodeLength'] );
							$isResume               = ( ! isset( $streamPositions[ $episode->id ] ) || ( $isSetPositionAndLength && $streamPositions[ $episode->id ]['Position'] < ( $streamPositions[ $episode->id ]['EpisodeLength'] - 60 ) ) );
							if ( $isNextEpisode && $isResume ) {
								$franchiseArtLink  = $franchiseURL . '/' . rljeApiWP_convertSeasonNameToURL( $season->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $episode->name );
								$franchiseArtTitle = 'Click to watch ' . $season->name . ': Episode ' . $episodeNumber;
								$break             = true;
								break;
							}
							if ( ! $isNextEpisode ) {
								$isNextEpisode = ( $episode->id === $streamPositionKeys[0] );
							}
						}
						if ( $break ) {
							break;
						}
					}
				}
			} elseif ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && is_numeric( $franchise->episodes[0]->id ) ) {
				$franchiseArtLink  = $franchiseURL . '/' . rljeApiWP_convertSeasonNameToURL( $franchise->seasons[0]->name ) . '/' . rljeApiWP_convertEpisodeNameToURLFriendly( $franchise->seasons[0]->episodes[0]->name );
				$franchiseArtTitle = 'Click to watch the first episode';
			}
			?>
<div itemscope itemtype="http://schema.org/TVSeries">
	<div class="secondary-bg" style="padding-bottom:50px">
		<div class="container franchise">
			<h4 class="subnav">
				<span class="subnav-prev hidden-xs hidden-sm">
					<a href="<?php echo $baseUrlPath; ?>">
						<img src="https://api.rlje.net/acorn/artwork/size/left-arrow?t=Icons" id="archive-arrows">
						<span>Back to Home</span>
					</a>
				</span>
				<span itemprop="name"><?php echo $franchise->name; ?></span>
				<meta itemprop="numberOfEpisodes" content="<?php echo $totalEpisodes; ?>" />
				<meta itemprop="numberOfSeasons" content="<?php echo count( $franchise->seasons ); ?>" />
				<span  class="subnav-next hidden-xs hidden-sm">
					<?php if ( ! empty( $firstEpisodeLink ) ) : ?>
					<a href="<?php echo esc_url( trailingslashit( $firstEpisodeLink ) ); ?>">
						<span>Watch Episode</span>
						<img src="https://api.rlje.net/acorn/artwork/size/right-arrow?t=Icons" id="archive-arrows">
					</a>
					<?php endif; ?>
				</span>
			</h4>
			<div class="row" >
				<div class="col-xs-12 col-sm-6 col-lg-4">
					<?php if ( ! empty( $franchiseArtLink ) ) : ?>
					<a href="<?php echo $franchiseArtLink; ?>" class="js-play-resume">
						<img itemprop="image" class="wp-post-image" id="franchise-avatar" title="<?php echo $franchiseArtTitle; ?>" src="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=460" />
					</a>
					<?php else : ?>
					<img itemprop="image" class="wp-post-image" id="franchise-avatar" src="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=460" />
					<?php endif; ?>
				</div>
				<div class="col-xs-12 col-sm-6 col-lg-8" >
					<p id="franchise-description" itemprop="description"><?php echo $franchise->longDescription; ?></p>
					<?php
					if ( isset( $_COOKIE['ATVSessionCookie'], $franchise->id ) && rljeApiWP_isUserActive( $_COOKIE['ATVSessionCookie'] ) && $haveFranchisesAvailable ) :
						if ( ! rljeApiWP_isFranchiseAddedToWatchlist( $franchise->id, $_COOKIE['ATVSessionCookie'] ) ) :
							?>
					<a class="inline"><button id="watchlistActionButton" onclick="addToWatchlist('<?php echo $franchise->id; ?>')">Add to Watchlist</button></a>
					<?php else : ?>
					<a class="inline"><button id="watchlistActionButton" onclick="removeFromWatchlist('<?php echo $franchise->id; ?>')">Remove from Watchlist</button></a>
					<?php endif; ?>
						<?php
						endif;
					?>
					<?php if ( isset( $franchise->episodes[0], $franchise->episodes[0]->id ) && is_numeric( $franchise->episodes[0]->id ) ) : ?>
					<span itemprop="trailer" itemscope itemtype="http://schema.org/VideoObject">
						<meta itemprop="thumbnailUrl" content="https://api.rlje.net/acorn/artwork/size/<?php echo $franchise->image; ?>?w=750" />
						<meta itemprop="description" content="<?php echo $franchise->longDescription; ?>" />
						<meta itemprop="name" content="<?php echo $franchise->name; ?>" />
						<meta itemprop="uploadDate" content="<?php echo ( isset( $franchise->episodes[0]->startDate ) && $franchise->episodes[0]->startDate != '' ) ? date( 'Y-m-d', $franchise->episodes[0]->startDate ) : ''; ?>" />
						<a class="inline" href="<?php echo $franchiseURL . '/trailer'; ?>">
							<button>View Trailer</button>
						</a>
					</span>
						<?php
						endif;
						set_query_var( 'seasons', $franchise->seasons );
						get_template_part( 'partials/seasons-dropdown' );
?>
				</div>
			</div>
		</div>
	</div>

			<?php
			if ( $haveFranchisesAvailable ) :
				if ( isset( $streamPositions ) ) :
					?>
	<!-- Continue Watching  -->
	<div class="container">
					<?php
					set_query_var( 'continueWatchingItems', $franchise->seasons );
					set_query_var( 'totalEpisodes', $totalEpisodes );
					set_query_var( 'streamposition', $streamPositions );
					get_template_part( 'partials/continue-watching-carousel' );
					?>
	</div>
					<?php
				endif;
				?>
	<div class="container episode">

		<!-- Seasons and Episodes -->
				<?php
				foreach ( $franchise->seasons as $seasonKey => $season ) {
					$season->seasonNumber = $seasonKey + 1;
					set_query_var( 'season', $season );
					if ( isset( $streamPositions ) ) {
						set_query_var( 'streamPositions', $streamPositions );
					}
					get_template_part( 'partials/list-episode-items' );
				}
				?>

		<!-- Viewers Also Watched -->
				<?php
				set_query_var( 'also_watched_items', rljeApiWP_getViewersAlsoWatched( $franchiseId ) );
				get_template_part( 'partials/viewers-also-watched' );
				?>
	</div>
				<?php
		else :
			 get_template_part( 'partials/franchises-unavailable-message' );
		endif;
		?>
</div>
			<?php
			get_footer();
		else :
			require_once get_404_template();
		endif;
	else :
		get_header();
		get_template_part( 'partials/plugin-deactivated-message' );
		get_footer();
	endif;
endif;
