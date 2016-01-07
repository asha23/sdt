<?php get_header(); ?>

<div class="container">
            <div class="row">
                        <?php get_template_part( 'library/template-parts/loops/loop', 'staff-cast' ); ?>
                        <?php get_template_part( 'library/template-parts/loops/loop', 'staff-creative' ); ?>
            </div>
</div>

<?php get_footer(); ?>
