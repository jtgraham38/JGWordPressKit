<?php
class JGWPSettingsGroup extends JGPluginParentReference{
    
    private $section_id;
    private $section_title;
    private $page;
    private $callback;

    public function __construct($plugin, $section_id, $section_title, $page, $callback){
        parent::__construct($plugin);   //call parent constructor
        $this->section_id = $section_id;
        $this->section_title = $section_title;
        $this->page = $page;
        $this->callback = $callback;
    }

    public function add(){
        add_settings_section(
            $this->section_id,      //id
            $this->section_title,   //title
            $this->callback,        //callback
            $this->page             //page to appear on
        );
    }
}