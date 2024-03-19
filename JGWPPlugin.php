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

    public function __construct() {

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

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin resources
        add_action('admin_enqueue_scripts', array($this, 'admin_resources'));

        //enqueue front-end resources
        add_action('wp_enqueue_scripts', array($this, 'front_end_resources'));

    }

    public function get_prefix(){
        return $this->plugin_prefix;
    }

    public function get_base_dir(){
        return $this->base_dir;
    }


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

    abstract public function admin_resources($hook);

    abstract public function front_end_resources($hook);
}

//class to make sure every object created has a reference to the plugin object
class JGPluginParentReference{
    protected $plugin;

    public function __construct($plugin){
        $this->plugin = $plugin;
    }
}

