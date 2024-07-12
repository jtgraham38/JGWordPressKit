<?php
namespace jtgraham\wpkit;


if (!defined('ABSPATH')) {
    exit;
}


abstract class PluginFeature {
    public $plugin;
    abstract function add_filters();
    abstract function add_actions();

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