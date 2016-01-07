<?php

if (!class_exists('Firegoby_AddEditorPlugin')) {
class Firegoby_AddEditorPlugin{

private $name = null;
private $url = null;
private $lang_path = null;
private $button_callback = null;
private $inits = array();

function __construct($plugin_name, $plugin_url,
    $lang_path = null, $button_callback = null, $inits = array())
{
    $this->name = $plugin_name;
    $this->url = $plugin_url;
    $this->lang_path = $lang_path;
    $this->inits = $inits;
    $this->button_callback = $button_callback;
}

public function register()
{
    add_filter('mce_external_plugins', array($this, 'external_plugins'));
    if ($this->lang_path) {
        add_filter('mce_external_languages', array($this, 'external_languages'));
    }
    if ($this->inits) {
        add_filter('tiny_mce_before_init', array($this, 'before_init'));
    }
    if ($this->button_callback) {
        add_filter('mce_buttons', $this->button_callback);
    }
}

public function before_init($inits){
    foreach ($this->inits as $key => $value) {
        $inits[$key] = $value;
    }
    return $inits;
}

public function external_plugins($plugins = array())
{
    $plugins[$this->name] = $this->url;
    return $plugins;
}

public function external_languages($langs)
{
    $langs[$this->name] = $this->lang_path;
        return $langs;
    }
}

}

