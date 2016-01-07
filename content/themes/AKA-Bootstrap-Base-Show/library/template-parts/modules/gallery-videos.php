<div id="video-gallery">
    <?php

    if( have_rows('video_gallery') ):
        while ( have_rows('video_gallery') ) : the_row();
            $title = get_sub_field('title');
            $videoImage = get_sub_field('video_image');
            $videoUrl = get_sub_field('video_url');
        ?>

            <div class="col-md-3" data-src="<?php echo $videoUrl; ?>">
                <h3><?php echo $title; ?></h3>

                <a href="<?php echo $videoUrl; ?>">
                    <img src="<?php echo $videoImage['url']; ?>" alt="<?php echo $title ?>" class="img-responsive"/>
                </a>

            </div>

    <?php endwhile; endif; ?>
</div>
