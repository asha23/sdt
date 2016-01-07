<?php get_header(); ?>

<div class="container">
	<section id="content" class="row">
		<article id="main-body">
			<?php get_template_part( 'library/template-parts/loops/loop', 'page' ); ?>
		</article>
	</section>
</div>

<?php get_footer(); ?>
