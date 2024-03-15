<?php
abstract class JGWPPlugin {

    protected $plugin_prefix = "";

    public function __construct($plugin_prefix) {

        //set plugin prefix, to use on settings, postmeta, etc. to avoid conflicts
        $this->plugin_prefix = $plugin_prefix;

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin resources
        add_action('admin_enqueue_scripts', array($this, 'admin_resources'));

        //enqueue front-end resources
        add_action('wp_enqueue_scripts', array($this, 'front_end_resources'));

    }

    //TODO: IMPLEMENT PLUGIN-WIDE PREFIX and better way of creating options!!!!

    abstract public function init_settings();

    abstract public function admin_resources($hook);

    abstract public function front_end_resources($hook);
}
