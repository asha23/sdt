<?php
/*
Plugin Name: TinyMCE Code Element
Plugin URI: http://wpist.me/
Description: Add button for code element to the rich editor.
Author: Takayuki Miyauchi
Version: 0.5.0
Author URI: http://wpist.me/
*/

define('TINYMCE_CODE_PLUGIN_URL', plugins_url('', __FILE__));

require_once(dirname(__FILE__).'/includes/AddEditorPlugin.php');

new TinyMCE_Code();

class TinyMCE_Code{

function __construct()
{
    if (is_admin()) {
        add_action('admin_head', array($this, 'admin_head'));
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
        add_filter('wp_fullscreen_buttons', array($this, 'wp_fullscreen_buttons'));
    }
}

public function admin_head()
{
    echo '<style type="text/css">';
    printf(
        'span.mce_code-element{background-image: url(%s) !important; background-position: center center !important;}',
        TINYMCE_CODE_PLUGIN_URL.'/mce_plugins/code-element/img/icon.png'
    );
    echo '</style>';
}

public function wp_fullscreen_buttons($buttons)
{
    $buttons[] = 'separator';
    $buttons['code-element'] = array(
        'title' => 'Code',
        'onclick' => "tinyMCE.execCommand('mceCodeElement');",
        'both' => false
    );
    return $buttons;
}

public function plugins_loaded()
{
    $mce = new Firegoby_AddEditorPlugin(
        'codeElement',
        TINYMCE_CODE_PLUGIN_URL.'/mce_plugins/code-element/editor_plugin.js',
        false,
        array($this, 'add_button'),
        false
    );
    $mce->register();
}

public function add_button($buttons){
    array_unshift($buttons, '|');
    array_unshift($buttons, 'codeElement');
    return $buttons;
}

}

