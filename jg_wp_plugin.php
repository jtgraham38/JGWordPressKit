<?php
abstract class JGWPPlugin {
    public function __construct() {

        //register settings
        add_action('admin_init', array($this, 'init_settings'));

        //enqueue admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_resources'));

        //enqueue front-end scripts
        add_action('wp_enqueue_scripts', array($this, 'front_end_resources'));

    }

    //TODO: IMPLEMENT PLUGIN-WIDE PREFIX!!!!


    abstract public function init_settings();

    abstract public function admin_resources($hook);

    abstract public function front_end_resources($hook);
}
