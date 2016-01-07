
<?php
// check if the repeater field has rows of data
if( have_rows('reviews') ):
  $starConstructor = "";
  while ( have_rows('reviews') ) : the_row();

    $stars = get_sub_field('stars');
    $quote = get_sub_field('review_quote');
    $source = get_sub_field('review_source');

    // Calculate stars
    if ($stars != 0) :
      for($i=1; $i<=$stars; $i++) :
        $starConstructor .= '<i class="fa fa-star"></i>';
      endfor;
    endif;
  ?>

  <?php if ($stars != 0) : ?>
    <div class="stars">
      <?php // Render stars
            echo $starConstructor;
            $starConstructor = "";
      ?>
    </div>
  <?php endif; ?>

  <?php if ($quote): ?>
    <header>
        <h2><?php echo $quote; ?></h2>
    </header>
  <?php endif; ?>

  <?php if ($source): ?>
    <div class="source">
      <?php echo $source; ?>
    </div>
  <?php endif; ?>

<?php endwhile; endif; ?>
