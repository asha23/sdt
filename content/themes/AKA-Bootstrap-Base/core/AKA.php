<?php

// require the navwalker

require_once( 'navwalker.php' );

add_action( 'after_setup_theme', 'AKA_ahoy', 16 );

function aka_ahoy() {
    // launching operation header cleanup
    add_action( 'init', 'aka_head_cleanup' );
    add_filter( 'the_generator', 'aka_rss_version' );
    add_filter( 'wp_head', 'aka_remove_wp_widget_recent_comments_style', 1 );
    add_action( 'wp_head', 'aka_remove_recent_comments_style', 1 );
    add_filter( 'gallery_style', 'aka_gallery_style' );
    add_action( 'wp_enqueue_scripts', 'aka_scripts_and_styles', 999 );
    add_filter( 'widget_text', 'do_shortcode');
    aka_theme_support();
    add_filter('body_class', 'theme_body_class');
    add_filter( 'the_content', 'aka_filter_ptags_on_images' );
    add_filter( 'excerpt_more', 'aka_excerpt_more' );
    add_action('admin_menu', 'remove_admin_menus');
}


function aka_head_cleanup() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'style_loader_src', 'aka_remove_wp_ver_css_js', 9999 );
	add_filter( 'script_loader_src', 'aka_remove_wp_ver_css_js', 9999 );
}

function aka_rss_version() {
	return '';
}

function aka_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

function aka_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

function aka_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

function aka_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


//******************************************************************************
// SCRIPTS & ENQUEUEING
//******************************************************************************


function aka_scripts_and_styles() {
	global $wp_styles;
	if (!is_admin()) {

		// Load asset manifest
		$assetstr = file_get_contents(dirname(dirname(__FILE__))."/build/manifest.json");
		$assets = json_decode($assetstr, true);
		$assets     = array(
			'css'       => '/build/css/min/styles.min.css' . '?' . $assets['build/css/min/styles.min.css']['hash'],
			'js'        => '/build/js/min/scripts.min.js' . '?' . $assets['build/js/min/scripts.min.js']['hash'],
		);

		wp_register_style( 'AKA-stylesheet', get_stylesheet_directory_uri() . $assets['css'], array(), '', 'all' );
		wp_enqueue_style( 'AKA-stylesheet' );

		wp_deregister_script('jquery');
		wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', false, '1.10.0');

		// This is the concatinated set of scripts (Keeps down HTML requests)
		wp_register_script( 'scripts', get_stylesheet_directory_uri() . $assets['js'], array(), '', true );

		// Do it.
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'scripts' );
	}
}

function aka_theme_support() {

	// wp thumbnails
	add_theme_support( 'post-thumbnails' );

	// Custom thumbnail sizes (add as many as you like)
	add_image_size( 'AKA-thumb-600', 600, 150, true );
	add_image_size( 'AKA-thumb-300', 300, 100, true );

	// wp custom background
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',  // background image default
	    'default-color' => '', // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'AKAtheme' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'AKAtheme' ) // secondary nav in footer
		)
	);
}


//******************************************************************************
// MENUS & NAVIGATION
//******************************************************************************

// the main menu
function aka_main_nav() {
	// display the wp3 menu if available - Suppress errors.
	if ( has_nav_menu( "main-nav" ) ) {
	    wp_nav_menu(array(
	    	'container' => false,                           // remove nav container
	    	'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
	    	'menu' => __( 'The Main Menu', 'AKAtheme' ),  // nav name
	    	'menu_class' => 'nav navbar-nav',  // adding custom nav class
	    	'theme_location' => 'main-nav',                 // where it's located in the theme
	    	'before' => '',                                 // before the menu
			'after' => '',                                  // after the menu
			'link_before' => '',                            // before each link
			'link_after' => '',                             // after each link
			'depth' => 2,                                   // limit the depth of the nav
	    	'walker' => new wp_bootstrap_navwalker()        // for bootstrap nav
		));
	};
} /* end AKA main nav */

// the footer menu (should you choose to use one)
function aka_footer_links() {
	// display the wp3 menu if available
    wp_nav_menu(array(
    	'container' => '',                              // remove nav container
    	'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    	'menu' => __( 'Footer Links', 'AKAtheme' ),   // nav name
    	'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
    	'theme_location' => 'footer-links',             // where it's located in the theme
    	'before' => '',                                 // before the menu
		'after' => '',                                  // after the menu
		'link_before' => '',                            // before each link
		'link_after' => '',                             // after each link
		'depth' => 0,                                   // limit the depth of the nav
    	'fallback_cb' => 'aka_footer_links_fallback', // fallback function
	));
} /* end AKA footer link */

// this is the fallback for header menu
function aka_main_nav_fallback() {
	wp_page_menu( array(
		'show_home' => true,
    	'menu_class' => 'nav top-nav clearfix',      // adding custom nav class
		'include'     => '',
		'exclude'     => '',
		'echo'        => true,
        'link_before' => '',                            // before each link
        'link_after' => ''                             // after each link
	) );
}


//******************************************************************************
// ASSORTED RANDOM CLEANUP ITEMS
//******************************************************************************

function aka_filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link and adds a swanky Bootstrap button and icon

function aka_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. get_permalink($post->ID) . '" title="'. __( 'Read', 'AKAtheme' ) . get_the_title($post->ID).'">'. __( '<p>&nbsp;</p><button class="btn btn-info">Read more <i class="fa fa-angle-double-right"></i></button>', 'AKAtheme' ) .'</a>';
}



//******************************************************************************
// ADD BODY CLASSES
//******************************************************************************

function theme_body_class($classes) {
	global $post;
	if (!$post) return $classes;
	$classes[] = 'page-'.$post->post_name;
	if ($post->post_parent) {
		$ppost = get_post($post->post_parent);
		$classes[] = 'section-'.$ppost->post_name;
	}
	return $classes;
}

//******************************************************************************
// SETUP PAGINATION
//******************************************************************************
function aka_pagination($pages = '', $range = 2, $nextPrev = 0, $next ='', $prev = '', $separator = '|'){
	$showitems = ($range * 2)+1;

	global $paged;
	if(empty($paged)) $paged = 1;

	if($pages == ''){
		global $wp_query;
		$pages = $wp_query->max_num_pages;

		if(!$pages){
			$pages = 1;
		}
	}

	if(1 != $pages){
		echo '<nav class="pagination">';
			if($nextPrev == 1){
				previousPage($prev,$separator);
			}

			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a href="'.fixCategory(get_pagenum_link(1)).'">&laquo;</a>';
			if($paged > 1 && $showitems < $pages) echo '<a href="'.fixCategory(get_pagenum_link($paged - 1)).'">&laquo;</a>';

			for ($i=1; $i <= $pages; $i++){
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					echo ($paged == $i)? '<span class="current"> '.$i.' </span>':'<a href="'.fixCategory(get_pagenum_link($i)).'" class="inactive" >'.$i.'</a>';
				}
			}

			if ($paged < $pages && $showitems < $pages) echo '<a href="'.fixCategory(get_pagenum_link($paged + 1)).'">&rsaquo;</a>';
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a href="'.fixCategory(get_pagenum_link($pages)).'">&raquo;</a>';

			if($nextPrev == 1){
				nextPage($next,$separator,$pages);
			}
		echo '</nav>';
	}
}

// Create previous button
function previousPage($prev,$separator){
	global $paged;
	if(empty($paged)) $paged = 1;
	if(empty($prev)) $prev = '&laquo;';
	if($paged != 1)
		echo '<a class="btn prev" href="'.fixCategory(get_pagenum_link($paged - 1)).'">'.$prev.'</a> '.$separator.' ';
}

// Create next button
function nextPage($next,$separator,$pages){
	global $paged;
	if(empty($paged)) $paged = 1;
	if(empty($next)) $next = '&raquo;';
	if($paged != $pages)
		echo ' '.$separator.' <a class="btn next" href="'.fixCategory(get_pagenum_link($paged + 1)).'">'.$next.'</a>';
}

// Fix category
function fixCategory($str){
	return $str;
	$base = get_bloginfo('url');
	return str_replace("{$base}/news/", "{$base}/category/news/", $str);
}

//******************************************************************************
// REMOVE TOP LEVEL ADMIN PAGES FROM SIDE MENU
//******************************************************************************


function remove_admin_menus() {
    // remove_menu_page( 'edit.php' ); // posts
    remove_menu_page( 'edit-comments.php' ); // comments
    // remove_menu_page( 'edit.php?post_type=page' ); // pages
}

//******************************************************************************
// REMOVE TOP LEVEL ADMIN PAGES FROM NAV BAR
//******************************************************************************
function mytheme_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'mytheme_admin_bar_render');

//******************************************************************************
// CUSTOMISE TITLE TAG
//******************************************************************************

add_filter( 'wp_title', 'rw_title', 10, 3 );
function rw_title( $title, $sep, $seplocation ) {
    global $page, $paged;

    // Don't affect in feeds.
    if ( is_feed() )
            return $title;

    // Add the blog name
    if ( 'right' == $seplocation )
            $title .= get_bloginfo( 'name' );
    else
            $title = get_bloginfo( 'name' ) . $title;

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
            $title .= " {$sep} {$site_description}";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
            $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
            return $title;
}

//******************************************************************************
// ADD THEME OPTION PAGE TO 'APPEARANCE' MENU
//******************************************************************************

function aka_theme_options() {
    add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'theme_options', 'aka_theme_options_page' );
}
add_action( 'admin_menu', 'aka_theme_options' );


//******************************************************************************
// REGISTER THEME OPTIONS SUPPORT
//******************************************************************************

function aka_register_settings() {
    register_setting( 'aka_theme_options', 'aka_options' );
}
add_action( 'admin_init', 'aka_register_settings' );


//******************************************************************************
// ENQUEUE SCRIPTS AND STYLES FOR THEME OPTIONS
//******************************************************************************

if (isset($_GET['page']) && $_GET['page'] == 'theme_options') {
	add_action('admin_print_scripts', 'admin_scripts');
	add_action('admin_print_styles', 'admin_styles');
}

function admin_scripts() {
	wp_enqueue_media();
	wp_register_script('theme-options-script', get_bloginfo('template_url') . '/core/js/min/theme-options.min.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('theme-options-script');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
}

function admin_styles() {
	wp_register_style('theme-options-styles', get_bloginfo('template_url') . '/core/css/theme-options.css');
	wp_enqueue_style('theme-options-styles');
	wp_enqueue_style('thickbox');
}

//******************************************************************************
// DASHBOARD WIDGET OVERRIDES
//******************************************************************************

function remove_dashboard_meta() {
        remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
        remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
        remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}
add_action( 'admin_init', 'remove_dashboard_meta' );





//******************************************************************************
// GENERATE CONTENT FOR THEME OPTIONS PAGE
//******************************************************************************

function aka_theme_options_page() {
    global $aka_options, $aka_categories, $aka_layouts;

    if ( ! isset( $_REQUEST['updated'] ) )
    $_REQUEST['updated'] = false; ?>

    <div class="wrap">

    <?php screen_icon(); echo "<h2>" .wp_get_theme() . __( ' Theme Options' ) . "</h2>"; ?>

    <?php if ( false !== $_REQUEST['updated'] ) : ?>
    	<div class="message"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
    <?php endif; // If the form has just been submitted, this shows the notification ?>

    <form id="options" method="post" action="options.php">

	    <?php $settings = get_option( 'aka_options', $aka_options ); ?>

	    <?php /* This function outputs some hidden fields required by the form,
	    including a nonce, a unique number used to ensure the form has been submitted from the admin page
	    and not somewhere else, very important for security */ ?>
	    <?php settings_fields( 'aka_theme_options' ); ?>

	 	<h3>Brand Logo</h3>

	    <table cellpadding="5" cellspacing="0" border="0">

		    <tr>
		    	<th><label for="brand_logo_button">Homepage Logo:</label></th>
			    <td>
			    	<img style="display: none;" id="brand_logo" src="<?php esc_attr_e($settings['brand_logo']); ?>">
			    </td>
			    <td>
			    	<input name="aka_options[brand_logo]" type="hidden" value="<?php esc_attr_e($settings['brand_logo']); ?>" />
			    	<input id="brand_logo_button" class="button upload-image" type="button" name="aka_options[brand_logo]" value="Upload Logo" />
			    	<?php if ( $settings['brand_logo'] != '') : ?>
			    		<input id="brand_logo_remove" class="button remove-image" type="button" value="Remove" />
			    	<?php endif; ?>
			    </td>
			    <td class="hint">Used for the home page only</td>
		    </tr>

		    <tr>
		    	<th><label for="brand_logo_inside_button">Inside page Logo:</label></th>
			    <td>
			    	<img style="display: none;" id="brand_logo_inside" src="<?php esc_attr_e($settings['brand_logo_inside']); ?>">
			    </td>
			    <td>
			    	<input name="aka_options[brand_logo_inside]" type="hidden" value="<?php esc_attr_e($settings['brand_logo_inside']); ?>" />
			    	<input id="brand_logo_inside_button" class="button upload-image" type="button" name="aka_options[brand_logo_inside]" value="Upload Logo" />
			    	<?php if ( $settings['brand_logo_inside'] != '') : ?>
			    		<input id="brand_logo_inside_remove" class="button remove-image" type="button" value="Remove" />
			    	<?php endif; ?>
			    </td>
			    <td class="hint">Used for all inside pages</td>
		    </tr>

	    </table>

	 	<h3>App Icons</h3>

	    <table cellpadding="5" cellspacing="0" border="0">

		    <tr>
		    	<th><label for="fav_icon_button">Favourites Icon:</label></th>
			    <td>
			    	<?php if ( $settings['fav_icon'] != '') : ?><img id="fav_icon" src="<?php esc_attr_e($settings['fav_icon']); ?>"><?php endif; ?>
			    </td>
			    <td>
			    	<input name="aka_options[fav_icon]" type="hidden" value="<?php esc_attr_e($settings['fav_icon']); ?>" />
			    	<input id="fav_icon_button" class="button upload-image" type="button" name="aka_options[fav_icon]" value="Upload Icon" />
			    	<?php if ( $settings['fav_icon'] != '') : ?><input id="fav_icon_remove" class="button remove-image" type="button" value="Remove" /><?php endif; ?>
			    </td>
			    <td class="hint">Used in the browser address bar</td>
		    </tr>
		    <tr>
		    	<th><label for="touch_icon_button">Apple Touch Icon:</label></th>
			    <td>
			    	<?php if ( $settings['touch_icon'] != '') : ?><img id="touch_icon" src="<?php esc_attr_e($settings['touch_icon']); ?>"><?php endif; ?>
			    </td>
			    <td>
			    	<input name="aka_options[touch_icon]" type="hidden" value="<?php esc_attr_e($settings['touch_icon']); ?>" />
			    	<input id="touch_icon_button" class="button upload-image" type="button" name="aka_options[touch_icon]" value="Upload Icon" />
			    	<?php if ( $settings['touch_icon'] != '') : ?><input id="touch_icon_remove" class="button remove-image" type="button" value="Remove" /><?php endif; ?>
			    </td>
			    <td class="hint">Used when saved to Apple device home screen</td>
		    </tr>

	    </table>

	    <?php submit_button( "Save Changes", "submit primary large", "submit" ) ?>

    </form>

    </div>

<?php } ?>
