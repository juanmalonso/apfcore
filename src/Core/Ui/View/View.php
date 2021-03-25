<?php

namespace Nubesys\Core\Ui\View;

use Nubesys\Core\Common;
use Nubesys\Core\Register;

class View extends Common {
    
    protected $defaultBasePath;
    protected $basePath;

    protected $vars;
    protected $templates;

    public function __construct($p_di, $p_basePath, $p_default = true)
    {
        parent::__construct($p_di);

        $this->vars                 = new Register();
        $this->templates            = new Register();

        $this->setTemplate("styles","");
        $this->setTemplate("script","");
        $this->setTemplate("template","");

        $this->basePath             = $p_basePath;

        if($p_default){

            $this->defaultBasePath      = $this->getDI()->get('config')->main->view->template->basepath;
        }else{

            $this->defaultBasePath      = $this->basePath;
        }
    }

    //VARS MANAGMENTS
    public function set($p_key, $p_value){

        $this->vars->set($p_key, $p_value);
    }

    public function get($p_key){

        return $this->vars->get($p_key);
    }

    public function getAll(){

        return $this->vars->all();
    }

    public function has($p_key){

        return $this->vars->has($p_key);
    }

    public function strAppend($p_key, $p_value){
        
        $this->vars->strAppend($p_key, $p_value);
    }

    //TEMPLATES MANAGMENT
    public function setTemplate($p_name, $p_code){

        $this->templates->set($p_name, $p_code);
    }

    public function getTemplate($p_name){

        return $this->templates->get($p_name);
    }

    public function hasTemplate($p_name){

        return $this->templates->has($p_name);
    }

    public function loadTemplates($p_templates = array()){

        foreach($p_templates as $template){

            if($this->getTemplate($template->name) == ""){

                $code = $this->loadTemplateFile($this->basePath, $template->name, $template->extension);

                if($code === FALSE){

                    $code = $this->loadTemplateFile($this->defaultBasePath, $template->name, $template->extension);
                }

                if($code != ""){

                    $this->setTemplate($template->name, $code);
                }
            }
        }
    }

    protected function loadTemplateFile($p_basepath, $p_name, $p_extension){

        $result = "";

        $path   = $p_basepath . "_" . $p_name . "." . $p_extension;
        
        if(file_exists($path)){

            $result = file_get_contents($path);
        }else{

            $result = FALSE;
        }

        return $result;
    }

    //VARS REPLACEMENT
    protected function replaceCallBack($p_matches){

        return $this->vars->get($p_matches[1]);
    }

    protected function replaceElementCallBack($p_matches){
        //var_dump($p_matches);

        //var_dump($this->vars->all());

        if($this->vars->has("Element" . $p_matches[1])){

            return $this->vars->get("Element" . $p_matches[1]);
        }else{
            
            return $p_matches[0];
        }
    }

    protected function replaceVars($p_code){
        
        //VARS REPLACE
        $result = preg_replace_callback('!\_\_\_(\w+)\_!', array($this,'replaceCallBack'), $p_code);

        //ELEMENT REPLACE
        $result = preg_replace_callback('!Element(\w+)!', array($this,'replaceElementCallBack'), $result);

        //TODO: REEPLAZOS MANUALES

        return $result;
    }

    //COMPILATION
    protected function compileCode($p_content, $p_vars){
        
        $result = $p_content;

        if($p_content !== false && $p_content !== ""){

            ob_start();

            extract($p_vars, EXTR_OVERWRITE);
            
            eval(' ?>'.$this->getDI()->get('voltService')->getCompiler()->compileString((is_null($p_content)) ? "" : $p_content));

            $result = ob_get_clean();
        }

        return $result;
    }

    //RENDERING
    public function renderTemplate($p_name){
        
        $code = "";

        if($this->hasTemplate($p_name)){

            $code = $this->renderCode($this->getTemplate($p_name));
        }

        return $code;
    }

    public function renderCode($p_code){
        
        $code = $this->compileCode($p_code, $this->getAll());

        return $this->replaceVars($code);
    }
    
    public function renderInto($p_name, $p_key){

        $this->set($p_key, $this->renderTemplate($p_name));
    }

    public function renderAppendInto($p_name, $p_key){

        $this->strAppend($p_key, $this->renderTemplate($p_name));
    }

}