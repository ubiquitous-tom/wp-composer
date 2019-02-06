<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="code" content="<?php echo ( $code = rljeApiWP_getCountryCode() ) ? $code : 'us'; ?>"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> style="padding-top: 0;">
	<?php
	do_action( 'rlje_tag_mananger_iframe' );
	?>
