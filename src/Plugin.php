<?php
namespace jtgraham38\jgwordpresskit;


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
        if (empty($this->prefix)) {
            throw new \Exception('You must set a plugin prefix.');
        }

        //ensure that plugin_base_dir is set
        if (empty($this->base_dir)) {
            throw new \Exception('You must set a plugin base directory.');
        }

        //ensure that plugin_base_url is set
        if (empty($this->base_url)) {
            throw new \Exception('You must set a plugin base URL.');
        }

        //register uninstall hook
        $this->register_uninstall();
    }

    //register a new feature with the plugin
    public function register_feature(string $key, PluginFeature $feature) {
        $feature->plugin = $this;
        $this->features[$key] = $feature;
    }

    //get a feature by key
    public function get_feature(string $key) {
        return $this->features[$key];
    }

    //echo a string with the plugin prefix
    public function pre($string) {
        echo $this->prefixed($string);
    }

    //get a string prefixed with the plugin prefix
    public function prefixed($string) {
        return $this->get_prefix() . '_' . $string;
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

    //run hooks for all features of the app, call this after all features are registered
    public function init() {
        foreach ($this->features as $key => $feature) {
            $feature->add_filters();
            $feature->add_actions();
        }
    }


    //handle uninstalling the plugin, by calling the uninstall method for all features
    //this should be hooked and called manually by the plugin developer, based on conditions they set for the plugin
    public function uninstall() {
        foreach ($this->features as $key => $feature) {
            //if the feature has an uninstall method, call it
            if (method_exists($feature, 'uninstall')) {
                $feature->uninstall();  //in this method, each feature should clean up any data it has registered
            }
        }
    }
};