<?php
    $images = get_field('gallery');

?>

<header>
    <h2>IMAGES</h2>
</header>

<div id="image-gallery" class="clearfix">
    <?php foreach( $images as $image ): ?>

        <div class="col-md-3" data-src="<?php echo $image['url']; ?>">

            <a href="<?php echo $image['url']; ?>">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="img-responsive" />
            </a>

            <p><?php echo $image['caption']; ?></p>

        </div>
    <?php endforeach; ?>
</div>

<header>
    <h2>VIDEOS</h2>
</header>

<div id="video-gallery" class="clearfix">
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
