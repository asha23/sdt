<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
	<?php
		$imgid = get_post_thumbnail_id( get_the_ID() );
		$thumb = wp_get_attachment_image_src( $imgid, 'thumbnail' );
		$alt = get_post_meta($imgid, '_wp_attachment_image_alt', true);
		if ($thumb) : ?>
			<img src="<?php echo $thumb[0]; ?>" alt="<?php echo $alt; ?>" title="<?php echo get_the_title($imgid); ?>">
		<?php endif; ?>
		<section id="biography">
			<hgroup>
				<h1><?php the_title(); ?></h1>
				<h2><?php $terms_as_text = get_the_term_list( $post->ID, 'role', '', ', ', '' ); echo strip_tags($terms_as_text); ?></h2>
			</hgroup>
			<?php the_content(); ?>

			<nav id="pageNav">
				<a class="btn small back" href="<?php echo get_category_link( get_cat_ID( 'cast' ) ); ?>">Go back</a>
			</nav>
		</section>
	<?php endwhile; ?>

<?php endif; ?>
