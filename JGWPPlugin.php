<?php
//require all dependencies
function require_all($dir) {
    foreach (glob($dir . '/*', GLOB_ONLYDIR) as $subdir) {
        require_all($subdir);
    }

    foreach (glob($dir . '/*.php') as $filename) {
        require_once $filename;
    }
}

require_all(plugin_dir_path(__FILE__));

//interface for creating a WordPress plugin
abstract class JGWPPlugin {

    protected $plugin_prefix;   //used to prefix settings, options, etc
    protected $settings_groups; //plugin settings groups, must contain array of JGWPSettingsGroup objects
    protected $settings;        //plugin settings
    protected $base_dir;        //plugin base directory
    protected $admin_resources;  //plugin admin resources
    protected $front_end_resources; //plugin front-end resources
    protected $base_url;        //plugin base url

    public function __construct($args = []) {
        //extract args
        $defaults = ['plugin_prefix'=>"jg_", 'base_dir'=> "", 'base_url'=>"",  'settings_groups'=>[], 'settings'=>[], 'admin_resources'=>[], 'front_end_resources'=>[]];
        $args = array_merge($defaults, $args);
        extract($args);

        //set variables
        $this->plugin_prefix = $plugin_prefix;
        $this->settings_groups = $settings_groups;
        $this->settings = $settings;
        $this->base_dir = $base_dir;
        $this->base_url = $base_url;
        $this->admin_resources = $admin_resources;
        $this->front_end_resources = $front_end_resources;

        //ensure that plugin_prefix is set
        if (empty($this->plugin_prefix)) {
            throw new Exception('You must set a plugin prefix.');
        }

        //ensure plugin base directory is set
        if (empty($this->base_dir)) {
            throw new Exception('You must set the base_dir property to the plugin base directory.');
        }

        //ensure that settings_group is an array of JGWPSettingsGroup objects
        if (!is_array($this->settings_groups)) {
            throw new Exception('You must set the settings_groups property to an array of JGWPSettingsGroup objects.');
        }

        //ensure that settings is an array of JGWPSetting objects
        if (!is_array($this->settings)) {
            throw new Exception('You must set the settings property to an array of JGWPSetting objects.');
        }

        //check if the last character of plugin prefix is an underscore
        if (substr($this->plugin_prefix, -1) !== '_') {
            // If not, add one
            $this->plugin_prefix .= '_';
        }

        //custom plugin hooks called here
        $this->plugin();

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin resources
        add_action('admin_enqueue_scripts', array($this, 'register_admin_resources'));

        //enqueue front-end resources
        add_action('wp_enqueue_scripts', array($this, 'register_front_end_resources'));

    }

    //custom implementation and features of the plugin
    protected abstract function plugin();

    public function init_settings(){
        //create settings sections and nested settings with them
        foreach ($this->settings_groups as $group) {
            $group->add();
        }

        //create settings that are not in a section
        foreach ($this->settings as $setting) {
            $setting->add();
        }
    }

    public function register_admin_resources($hook){
        //add resources
        foreach ($this->admin_resources as $resource) {
            $resource->add();
        }
    }

    public function register_front_end_resources($hook){
        //add resources
        foreach ($this->front_end_resources as $resource) {
            $resource->add();
        }
    }

    //getters used by other JGWP classes
    public function get_prefix(){
        return $this->plugin_prefix;
    }

    public function get_base_dir(){
        return $this->base_dir;
    }

    public function get_base_url(){
        return $this->base_url;
    }
}

//class to make sure every object created has a reference to the plugin object
abstract class JGWPPluginItem{
    protected $plugin;

    public function __construct($plugin){
        $this->plugin = $plugin;
    }

    abstract public function add();
}

