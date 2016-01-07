<?php
/* 
Plugin Name: NoFollowr
Plugin URI: http://nofollowr.com
Description:When logged in as an administrator, green "tick" and red "stop" icons appear next to all external links in a post indicating whether rel="nofollow" is currently applied to them. Simply click an icon to toggle between these two states and alter the link's nofollow status. This change is remotely reflected in the database without requiring a page reload.
Version: 1.0.3
Author: Joel Birch
Author URI: http://nofollowr.com
License: GPLv2
*/

/*  Copyright 2013  Joel Birch  (email : joeldbirch@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class NoFollowr {

  var $_trigger = 'nofollowr';
  var $version = '1.0.3';
  
  //Constructor
  function NoFollowr() {
    
    $this->_version_check();
    
    if( empty($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
      add_action( 'init', array( &$this, '_add_assets' ) );
      add_action( 'the_content', array( &$this, '_wrap_content' ) );
    } else if( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      add_filter( 'query_vars', array( &$this, '_add_trigger' ));
      add_action( 'template_redirect', array( &$this, '_trigger_check' ) );
    }

  }
  
  function _version_check() {
    if ( is_admin() ) {
      global $wp_version;
      $exit_msg='
      <style media="screen">
        * {
          font: bold 12px/1 "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
          margin: 0;
          padding: 0;
        }
        p {
          padding: 2em;
          background: white;
          border: 1px solid #E6DB55;
        }
      </style>
      <p>NoFollowr requires WordPress 2.7 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update.</a></p>';
      if (version_compare($wp_version,"2.7","<")) {
        exit ($exit_msg);
      }
    }
  }
  
  function _add_assets() {
    if (!is_admin() && current_user_can( 'manage_options' ) ) {
      $plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
      wp_enqueue_style( 'NoFollowr', $plugin_url.'css/NoFollowr-min.css', null, $this->version );
      wp_enqueue_script('NoFollowr', $plugin_url.'js/NoFollowr-min.js', array('jquery'), $this->version, true);
      $params = array(
        'ajaxURL' => get_bloginfo('url').'/?'.$this->_trigger.'=1',
        'postSelector' => '.jbPost'
      );
      wp_localize_script( 'NoFollowr', 'jbirchPlugin', $params );
    }
  }
  
  function _wrap_content($content) {
    global $post;
    if ( current_user_can( 'manage_options' ) ) {
      $content = '<div id="jbID-'.$post->ID.'" class="jbPost">'.$content.'</div>';
    }
    return $content;
  }
  
  function _add_trigger($vars) {
      $vars[] = $this->_trigger;
      return $vars;
  }
  
  function _trigger_check() {
    if(intval(get_query_var($this->_trigger)) == 1) {
      echo $this->_ajaxHandler();
      exit;
    }
  }

  function _delete_last_revision( $id ) {
    $lastRevision = wp_get_post_revisions( $id, array('numberposts' => 1) );
    foreach($lastRevision as $revision) {
      wp_delete_post_revision( $revision );
    }
  }

  function _ajaxHandler() {
    $postid = $_POST['postid'];
    $nofollow = $_POST['nofollow'];
    $href = $_POST['href'];
    if (
      array_key_exists('postid', $_REQUEST)
      && is_numeric($postid)
      && array_key_exists('nofollow', $_REQUEST)
      && array_key_exists('href', $_REQUEST)
      && current_user_can( 'edit_post', $postid )
    ) {
      // Get the content
      $thepost = get_posts(array(
        'include' => $postid,
        'post_status' => 'any',
        'post_type' => 'any'
        )
      );
    
      if ( count($thepost) !== 1) {
        return false;
      }
      $thecontent = $thepost[0]->post_content;
    
      if ('true' === $nofollow) {
        $thecontent = $this->_add_nofollow($href,$thecontent);
      } else {
        $thecontent = $this->_remove_nofollow($href,$thecontent);
      }
    
    // Update post $updateID
      $my_post = array();
      $my_post['ID'] = $postid;
      $my_post['post_content'] = $thecontent;

    // Update the post into the database
      if ( wp_update_post( $my_post ) ) {
        $this->_delete_last_revision( $postid );
        return 'success';
      } else {
        return 'failed';
      }
    } else {
      return 'failed';
    }
  }

  function _add_nofollow_callback( $matches ) {
    $orig = $matches[0];
    $matches[0] = preg_replace( "| rel=([\"\']??)([^\"\'>]*?)\\1|siU" , ' rel="$2 nofollow"', $matches[0]);
    if ($matches[0] === $orig ) {
      $matches[0] = stripslashes(wp_rel_nofollow($matches[0]));
    }
    return $matches[0];
  }
  
  function _add_nofollow( $find, $content ){
    $pattern = '|<a\s[^>]*href=([\"\']??)('.preg_quote($find).'[^\" >]*?)\\1[^>]*>(.*)<\/a>|siU';
    $content = preg_replace_callback(
      $pattern,
      create_function(
        '$matches',
        'return NoFollowr::_add_nofollow_callback($matches);'
      ),
      $content
    );
    return $content;
  }

  function _remove_nofollow_callback( $matches ) {
    $rel = array();
    preg_match( '| rel=[\"\'](.+)[\"\']|si', $matches[0], $rel);
    $remainingVal = str_replace( 'nofollow', '', $rel[1] );
    $remainingVal = trim($remainingVal);
    $replacement = ($remainingVal==='') ? '' : ' rel="'.$remainingVal.'"';
    $matches[0] = str_replace( $rel[0], $replacement, $matches[0]);
    return $matches[0];
  }

  function _remove_nofollow( $find, $content ) {
    $pattern = '|<a\s[^>]*href=([\"\']??)('.preg_quote($find).'[^\" >]*?)\\1[^>]*>(.*)<\/a>|siU';
    $content = preg_replace_callback(
      $pattern,
      create_function(
        '$matches',
        'return NoFollowr::_remove_nofollow_callback($matches);'
      ),
      $content
    );
    return $content;
  }

} //end class

$no_followr = new NoFollowr;
