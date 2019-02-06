<?php
// $environment = apply_filters( 'atv_get_extenal_subdomain', '' ); // Leave the else value empty to production, now is .dev because it is not implemented in prod yet (used in uat.acorn.tv).
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="code" content="<?php echo ( $code = rljeApiWP_getCountryCode() ) ? $code : 'us'; ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php
	do_action( 'rlje_tag_mananger_iframe' );
	do_action( 'rlje_header_navigation' );
	?>
