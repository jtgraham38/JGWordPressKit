<?php
namespace jtgraham38\jgwordpresskit;


if (!defined('ABSPATH')) {
    exit;
}


class Plugin {
    public array $features = [];  //array of feature objects, this class is extended to break the plugin into organized chunks
    private array $config;         //array of config values, this is set by the plugin developer, to allow for a single source of truth for the plugin.  Read only.

    protected string $prefix;          //used to prefix settings, options, etc
    protected string $base_dir;        //plugin base directory
    protected string $base_url;        //plugin base url

    //constructor
    public function __construct(string $prefix, string $base_dir, string $base_url, array $config = []) {
        $this->prefix = $prefix;
        $this->base_dir = $base_dir;
        $this->base_url = $base_url;
        $this->config = $config;

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

        //if the prefix does not end with an underscore, add one
        if (substr($this->prefix, -1) !== '_') {
            $this->prefix .= '_';
        }
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
        return $this->get_prefix() . $string;
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

    //get a config value
    public function config(string $key) {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null;
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