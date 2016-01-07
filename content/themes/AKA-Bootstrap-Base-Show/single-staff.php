<?php get_header();

    $image = get_field('image');
    $role = get_field('role');
?>

<div class="container">
	<div class="row">
		<section>

            <header>
                <h1><?php the_title(); ?></h1>
                <h3><?php echo $role; ?></h3>
            </header>

            <div class="left">
                <?php if($image) : ?>
                    <img src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>" >
                <?php endif; ?>
            </div>

            <div class="right">
                <?php the_content(); ?>
            </div>

		</section>
	</div>
</div>

<?php get_footer(); ?>
