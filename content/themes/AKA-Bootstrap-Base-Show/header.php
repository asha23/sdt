<?php
	global $aka_options;
	$aka_settings = get_option('aka_options', $aka_options);
	$brand_logo = $aka_settings['brand_logo'];
	$brand_logo_inside = ( $aka_settings['brand_logo_inside'] == '' ) ? $brand_logo : $aka_settings['brand_logo_inside'];
?>

<!DOCTYPE html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
	<meta charset="utf-8">

	<?php // Google Chrome Frame for IE ?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php wp_title(''); ?></title>

	<script type="text/javascript">
		var domain = '<?php bloginfo('template_url'); ?>';
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var templateurl = '<?php echo get_bloginfo('template_url'); ?>';
	</script>

	<?php // mobile meta ?>

	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<?php if ( $aka_settings['fav_icon'] != '' ) : ?><link rel="shortcut icon" href="<?php echo $aka_settings['fav_icon']; ?>"><?php endif; ?>
	<?php if ( $aka_settings['touch_icon'] != '' ) : ?><link rel="apple-touch-icon" href="<?php echo $aka_settings['touch_icon']; ?>"><?php endif; ?>

	<!--[if IE]>
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	<![endif]-->

	<meta name="msapplication-TileColor" content="#f01d4f">
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php wp_head(); ?>

	<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script><![endif]-->
</head>

<body <?php body_class(); ?>>

<?php // drop Google Analytics Here ?>


<?php // end analytics ?>

<header id="header" class="container">
    <div class="row">

		<nav class="navbar navbar-default" role="navigation">
			<div class="navbar-header">

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<i class="fa fa-bars"></i>
				</button>

				<a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="homepage" class="navbar-brand">
					<?php if ( $brand_logo ) : ?><img src="<?php echo $brand_logo; ?>" alt="<?php wp_title(); ?>" title="<?php wp_title(); ?>">
					<?php else : bloginfo('title'); ?>
					<?php endif; ?>
				</a>

			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<?php aka_main_nav(); ?>
				<?php // get_search_form(); ?>

			</div>
		</nav>

    </div>
</header>

<!-- Inside logo if needed
<?php if ( $brand_logo_inside ) : ?><img src="<?php echo $brand_logo_inside; ?>" alt="<?php wp_title(); ?>" title="<?php wp_title(); ?>">
<?php else : bloginfo('title'); ?>
<?php endif; ?>
-->
