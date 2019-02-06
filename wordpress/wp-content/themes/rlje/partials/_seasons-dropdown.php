<?php
global $wp_query;

$base_url_path = ( function_exists( 'rljeApiWP_getBaseUrlPath' ) ) ? rljeApiWP_getBaseUrlPath() : '';
$season_name   = isset( $wp_query->query_vars['seasonName'] ) ? $wp_query->query_vars['seasonName'] : null;
$seasons       = $wp_query->query_vars['seasons'];
$franchise_id  = $wp_query->query_vars['franchise_id'];

if ( count( $seasons ) > 1 ) :
	?>

<!-- Drop Down Series Filter -->
<div id="cover">
	<div id="options">
		<a><?php echo ( isset( $season_name ) ) ? $season_name : 'Filter By Series'; ?></a>
		<span id="clicker">
			<img src="https://api.rlje.net/acorn/artwork/size/dropdown-arrow?t=Icons" width="13" style="opacity:.7"/>
		</span>
	</div>
	<ul id="drop-select" class="closed">
		<?php for ( $i = 0; $i < count( $seasons ); $i++ ) : ?>
		<a href="<?php echo esc_url( home_url( trailingslashit( $franchise_id . '/' . rljeApiWP_convertSeasonNameToURL( $seasons[ $i ]->name ) ) ) ); ?>"><li><?php echo $seasons[ $i ]->name; ?></li></a>
		<?php endfor; ?>
		<a href="<?php echo esc_url( home_url( trailingslashit( $franchise_id ) ) ); ?>"><li>View All</li></a>
	</ul>
</div>

	<?php
endif;
