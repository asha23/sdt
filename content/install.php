<?php

add_action('wp_install', 'aka_wp_install');

function aka_wp_install($user)
{
    // Set default theme
    $dtheme = wp_get_theme('AKA-Bootstrap-Base');
    if($dtheme->exists() && $dtheme->is_allowed()) {
        switch_theme($dtheme->get_stylesheet());
    }

    // Set blog description (tagline) to blank
    update_option('blogdescription', '');

    //******************************************************************************
    // ACTIVATE DEFAULT PLUGINS
    //******************************************************************************

      function run_activate_plugin( $plugin ) {
      $current = get_option( 'active_plugins' );
      $plugin = plugin_basename( trim( $plugin ) );

      if ( !in_array( $plugin, $current ) ) {
    	  $current[] = $plugin;
    	  sort( $current );
    	  do_action( 'activate_plugin', trim( $plugin ) );
    	  update_option( 'active_plugins', $current );
    	  do_action( 'activate_' . trim( $plugin ) );
    	  do_action( 'activated_plugin', trim( $plugin) );
      }

      return null;
    }
    run_activate_plugin( 'advanced-custom-fields-pro/acf.php' );
    run_activate_plugin( 'akasettings/akasettings.php' );
    run_activate_plugin( 'wp-migrate-db-pro/wp-migrate-db-pro.php' );
    run_activate_plugin( 'manual-image-crop/manual-image-crop.php' );
    run_activate_plugin( 'safe-redirect-manager/safe-redirect-manager.php' );
    run_activate_plugin( 'title-and-nofollow-for-links/title-and-nofollow-for-links.php' );
    run_activate_plugin( 'akapostorderbytaxonomy/akapostorderbytaxonomy.php' );
    run_activate_plugin( 'post-type-archive-links/post-type-archive-links.php' );

}

?>

<?php

/*

        This is done on theme installation.
        It adds some default pages and deletes the Sample Page from the installation

        Todo. Extend this so that it installs all the common pages for a show then
        split this out into a new theme type

 */

if (isset($_GET['activated']) && is_admin()){

        $pages = array(
                'Home' => array (
                        'Home Template' => 'front-page.php'
                ),
                'Terms and Conditions' => array ( // Page title
                        'Terms Content' => '' // Content to use (Use a url)
                ),
                'Cookie Policy' => array (
                        'Cookies Template' => ''
                ),
                'Privacy Policy' => array (
                        'Privacy Template' => ''
                )


        );

        foreach ($pages as $page_url_title => $page_meta) {
                $id = get_page_by_title($page_url_title);

                foreach($page_meta as $page_content => $page_template) {
                        $page = array (
                                'post_type' => 'page',
                                'post_title' => $page_url_title,
                                'post_name' => $page_url_title,
                                'post_status' => 'publish',
                                'post_content' => $page_content,
                                'post_author' => 1,
                                'post_parent' => ''
                        );
                };

                if (!isset($id -> ID)) {
                        $new_page_id = wp_insert_post($page);
                        if(!empty($page_template)) {
                                update_post_meta($new_page_id, '_wp_page_template', $page_template);
                        };
                };
        };

        // Find and delete the WP default 'Sample Page'

        $defaultPage = get_page_by_title('Sample Page');
        if($defaultPage) {
                wp_delete_post( $defaultPage->ID );
        }

        $post = get_page_by_path('hello-world',OBJECT,'post');
        if ($post) wp_delete_post($post->ID,true);

};
