<?php
get_header();

$have_franchises_available = apply_filters( 'atv_haveFranchisesAvailableByCountry', 'homepage');
?>

<div class="content">

<?php

if ( $have_franchises_available ) {
	?>
	<?php do_action( 'rlje_homepage_top_section_content' ); ?>

	<?php do_action( 'rlje_homepage_middle_section_content' ); ?>

	<?php do_action( 'rlje_homepage_bottom_section_content' ); ?>

	<?php
} else {
	get_template_part( 'templates/franchisesUnavailable' );
};
?>

</div>

<?php

get_footer();
