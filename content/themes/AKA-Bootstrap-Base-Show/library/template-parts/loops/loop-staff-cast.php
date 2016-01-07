<section class="clearfix">

    <header>
        <h2>CAST</h2>
    </header>

    <?php // Cast loop
        $loopCast = new WP_Query( array( 'post_type' => 'staff', 'staff_category' => 'cast', 'posts_per_page' => -1 ) );
        if($loopCast->have_posts()):
            while($loopCast->have_posts()):
                $loopCast->the_post();
                    $role = get_field('role');
                    $image = get_field('image');
                ?>
                <div class="col-md-4">
                    <hgroup>
                        <h2><?php the_title(); ?></h2>

                        <?php if($role) : ?>
                            <h3><?php echo $role ?></h3>
                        <?php endif; ?>
                    </hgroup>

                    <?php if($image) : ?>
                        <figure>
                            <img src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>" class='img-responsive' />
                        </figure>
                    <?php endif; ?>

                    <a href="<?php the_permalink(); ?>" class="btn">View details</a>
                </div>
            <?php endwhile;
        endif;
        wp_reset_postdata();
    ?>
</section>
