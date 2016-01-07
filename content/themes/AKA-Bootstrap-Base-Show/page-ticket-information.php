<?php
/*
	Template Name: Ticket Information
*/
get_header();
?>

<div class="container">
	<div class="row">
		<?php get_template_part( 'library/template-parts/loops/loop', 'ticket-information' ); ?>
	</div>
</div>

<?php get_footer(); ?>
