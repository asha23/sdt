<?php
/*
	Template Name: Reviews
*/
get_header();
?>

<div class="container">
	<div class="row">
		<?php get_template_part( 'library/template-parts/loops/loop', 'reviews' ); ?>
	</div>
</div>

<?php get_footer(); ?>
