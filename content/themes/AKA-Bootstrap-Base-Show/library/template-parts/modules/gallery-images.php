<?php
    $images = get_field('gallery');
?>

<div id="image-gallery">
    <?php foreach( $images as $image ): ?>

        <div class="col-md-3" data-src="<?php echo $image['url']; ?>">

            <a href="<?php echo $image['url']; ?>">
                <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" class="img-responsive" />
            </a>

            <p><?php echo $image['caption']; ?></p>

        </div>
    <?php endforeach; ?>
</div>
