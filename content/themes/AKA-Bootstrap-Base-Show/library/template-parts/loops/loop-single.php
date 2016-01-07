

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>

		<section>
			<hgroup>
				<h1><?php the_title(); ?></h1>
			</hgroup>
			<?php the_content(); ?>

		</section>
	<?php endwhile; ?>

<?php endif; ?>
