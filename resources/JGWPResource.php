<?php
class JGWPResource extends JGWPPluginItem{

    protected $filename;
    protected $type;
    protected $dependencies;
    protected $id;
    protected $extension;

    public function __construct($plugin, $filename, $dependencies = []){
        parent::__construct($plugin);   //call parent constructor
        //setting args
        $this->filename = $filename;
        $this->dependencies = $dependencies;
        $this->id = pathinfo($filename, PATHINFO_FILENAME);
        $this->extension = pathinfo($filename, PATHINFO_EXTENSION);

    }

    public function add(){
        //register resource
        if ($this->extension === 'js'){
            wp_enqueue_script(
                $this->plugin->get_prefix() . $this->id . "_" . $this->type,
                $this->plugin->get_base_url() . "resources/js/" . $this->filename,
                $this->dependencies,
            );
        } else if ($this->extension === 'css'){
            wp_enqueue_style(
                $this->plugin->get_prefix() . $this->id . "_" . $this->type,
                $this->plugin->get_base_url() . "resources/css/" . $this->filename,
                $this->dependencies,
            );
        }
    }
}