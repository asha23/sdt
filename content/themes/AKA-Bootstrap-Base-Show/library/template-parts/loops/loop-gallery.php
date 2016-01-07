<?php
    // Multiple gallery template
?>

<section class="clearfix">

    <header>
        <h2>IMAGE GALLERIES</h2>
    </header>

    <?php // Image loop
        $loopImage = new WP_Query( array( 'post_type' => 'galleries', 'galleries_category' => 'image', 'posts_per_page' => -1 ) );
        if($loopImage->have_posts()):
            while($loopImage->have_posts()):
                $loopImage->the_post();
                    $coverImg = get_field('gallery_cover_image');
                ?>
                <article class="col-md-3">
                    <header>
                        <h3><?php the_title(); ?></h3>
                    </header>

                    <?php if($coverImg) : ?>
                    <figure>
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo $coverImg['url']; ?>" alt="<?php the_title(); ?>" class="img-responsive">
                        </a>
                    </figure>
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="btn">View more</a>
                </article>

            <?php endwhile;
        endif;
        wp_reset_postdata();
    ?>
</section>

<section class="clearfix">

    <header>
        <h2>VIDEO GALLERIES</h2>
    </header>

    <?php // Video loop
        $loopVideo = new WP_Query( array( 'post_type' => 'galleries', 'galleries_category' => 'video', 'posts_per_page' => -1 ) );
        if($loopVideo->have_posts()):
            while($loopVideo->have_posts()):
                $loopVideo->the_post();
                    $coverImg = get_field('video_cover_image');
                ?>
                <article class="col-md-3">
                    <header>
                        <h3><?php the_title(); ?></h3>
                    </header>

                    <?php if($coverImg) : ?>
                        <figure>
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo $coverImg['url']; ?>" alt="<?php the_title(); ?>" class="img-responsive">
                            </a>
                        </figure>
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="btn">View more</a>

                </article>

            <?php endwhile;
        endif;
        wp_reset_postdata();
    ?>

</section>
