<?php
// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
class JGWPSetting extends JGWPPluginItem{
    private $name;
    private $args;

    private $lbl;
    private $page;
    private $section_id;


    public function __construct($plugin, $name, $args, $page, $lbl, $section_id = null){
        parent::__construct($plugin);   //call parent constructor
        //setting args
        $this->name = $name;
        $this->args = $args;
        //field args
        $this->page = $page;
        $this->section_id = $section_id;
        $this->lbl = $lbl;
    }

    public function add(){
        //register setting
        register_setting(
            $this->plugin->get_prefix() . "settings",    //TODO: switch this to the plugin prefix + "settings"
            $this->plugin->get_prefix() . $this->name,    //setting name
            $this->args    //args
        );

        //register field
        add_settings_field(
            $this->name . '_field',                 //id
            $this->lbl,                             //title
            function(){
                require $this->plugin->get_base_dir() . 'elements/settings/' . $this->name . '_field.php';
            },                                      //callback to get input from "elements/settings/" + $this.name + "_field.php"
            $this->page,                            //page
            isset($this->section_id) ? $this->section_id : 'default'                            //section id to appear on
        );
    }
}