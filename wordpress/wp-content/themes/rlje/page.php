<?php
 /**
  * The template for acorntv landing page.
  */

get_header(); ?>

<?php
while ( have_posts() ) :
	the_post();
	?>

<section class="content">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="page-hero">
			<div class="container">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</div>
		</header><!-- .entry-header -->
		<section class="page-body">
			<div class="container">
			<?php
			if ( ( ! post_password_required() || ! is_attachment() || has_post_thumbnail() ) ) {
				if ( is_singular() ) {
					?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail(); ?>
					</div><!-- .post-thumbnail -->

				<?php } else { ?>

					<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
						<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
					</a>

				<?php } ?>
			<?php } ?>

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
				?>
			</div><!-- .entry-content -->
			</div>
		</section>
	</article><!-- #post-page## -->
</section>
<?php endwhile; ?>

<?php get_footer(); ?>
