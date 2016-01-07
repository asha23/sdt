
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

?>
