<?php
/*
	Template Name: About the show
*/
get_header();
?>

<div class="container">
	<div class="row">
		<?php get_template_part( 'library/template-parts/loops/loop', 'about' ); ?>
	</div>
</div>

<?php get_footer(); ?>
