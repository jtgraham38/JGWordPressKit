<?php
namespace jtgraham\jgwordpresskit;


if (!defined('ABSPATH')) {
    exit;
}


class Plugin {
    public $features = [];  //array of feature objects, this class is extended to break the plugin into organized chunks

    protected $prefix;          //used to prefix settings, options, etc
    protected $base_dir;        //plugin base directory
    protected $base_url;        //plugin base url

    //constructor
    public function __construct($prefix, $base_dir, $base_url) {
        $this->prefix = $prefix;
        $this->base_dir = $base_dir;
        $this->base_url = $base_url;

        //ensure that plugin_prefix is set
        if (empty($this->plugin_prefix)) {
            throw new Exception('You must set a plugin prefix.');
        }

        //ensure that plugin_base_dir is set
        if (empty($this->plugin_base_dir)) {
            throw new Exception('You must set a plugin base directory.');
        }

        //ensure that plugin_base_url is set
        if (empty($this->plugin_base_url)) {
            throw new Exception('You must set a plugin base URL.');
        }
    }

    //register a new feature with the plugin
    public function register_feature($feature) {
        $feature->plugin = $this;
        $this->features[] = $feature;
    }

    //get plugin prefix
    public function get_prefix() {
        return $this->prefix;
    }
    //get plugin base directory
    public function get_base_dir() {
        return $this->base_dir;
    }
    //get plugin base url
    public function get_base_url() {
        return $this->base_url;
    }

    //run hooks for all features of the app
    public function init() {
        foreach ($this->features as $feature) {
            $feature->add_filters();
            $feature->add_actions();
        }
    }
};