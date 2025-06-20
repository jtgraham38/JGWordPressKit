<?php
namespace jtgraham38\jgwordpresskit;


if (!defined('ABSPATH')) {
    exit;
}


abstract class PluginFeature {
    public $plugin;
    abstract function add_filters();
    abstract function add_actions();

    //get a feature by key
    public function get_feature(string $key) {
        return $this->plugin->get_feature($key);
    }

    //echo a string with the plugin prefix
    public function pre($string, $esc_mode = 'attr') {
        if ($esc_mode == 'attr') {
            echo esc_attr($this->prefixed($string));
        } else if ($esc_mode == 'html') {
            echo esc_html($this->prefixed($string));
        } else {
            echo $this->prefixed($string);
        }
    }

    //get a string prefixed with the plugin prefix
    public function prefixed($string) {
        return $this->plugin->prefixed($string);
    }

    //get a config value
    public function config(string $key) {
        return $this->plugin->config($key);
    }

    //get plugin prefix
    public function get_prefix() {
        return $this->plugin->get_prefix();
    }

    //get plugin base directory
    public function get_base_dir() {
        return $this->plugin->get_base_dir();
    }

    //get plugin base url
    public function get_base_url() {
        return $this->plugin->get_base_url();
    }
}