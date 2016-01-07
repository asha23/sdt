<?php get_header();

// Determine type of gallery and return the correct code
// Simply do it by the custom field Name

$images = get_field('gallery');
$video = get_field('video_gallery');

?>

<div class="container">
    <div class="row">
        <section>

            <header>
                <h1><?php the_title(); ?></h1>
            </header>

            <div class="">
                <?php the_content(); ?>
            </div>

            <?php // Render the galleries ?>
            <?php // do an image gallery (Lightgallery dependent) ?>

            <?php if ($images) : ?>
                <?php get_template_part( 'library/template-parts/modules/gallery', 'images' ); ?>
            <?php endif; ?>

            <?php // do a video gallery (Lightgallery dependent) ?>

            <?php if ($video): ?>
                <?php get_template_part( 'library/template-parts/modules/gallery', 'videos' ); ?>
            <?php endif; ?>

        </section>
    </div>
</div>



<?php get_footer(); ?>
