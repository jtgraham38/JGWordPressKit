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

    //run hooks for all features of the app
    public function init() {
        foreach ($this->features as $key => $feature) {
            $feature->add_filters();
            $feature->add_actions();
        }
    }

     //Register plugin for uninstall
     //Call this if you want to uninstall the data registered by the plugin
    public function register_uninstall() {
        \add_action($this->prefix . '_uninstall', [$this, 'do_uninstall']);
    }

    //Uninstall all features of the plugin and the plugin itself
    //Call this from uninstall.php using: Plugin::uninstall('your_prefix_');
    public static function uninstall($prefix) {
        // Ensure this is called only during uninstall
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            exit;
        }

        // Run plugin-specific uninstall hook
        \do_action($prefix . '_uninstall');

        // Clean up all options that start with the plugin prefix
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                $prefix . '_%'
            )
        );
    }

    //Optional uninstall method that can be implemented by child class
    protected function do_uninstall() {
        // Child class can override this method
        foreach ($this->features as $key => $feature) {
            if (method_exists($feature, 'uninstall')) {
                $feature->uninstall();
            }
        }
    }
};