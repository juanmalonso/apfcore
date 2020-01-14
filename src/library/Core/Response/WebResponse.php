<?php

namespace Nubesys\Core\Response;

use Nubesys\Core\Response\Response;
use Nubesys\Core\Register;

class WebResponse extends Response {

    protected $html;
    protected $cookies;
    protected $redirect;

    public function __construct($p_di)
    {
        parent::__construct($p_di);

        $this->redirect     = false;
        $this->type         = "web";
        $this->html         = "EMPTY";

        $this->cookies      = new Register();
    }

    public function getBody()
    {
        return $this->html;
    }

    public function setHtml($p_html){

        $this->html     = $p_html;
    }

    public function setRedirect($p_url){

        $this->redirect  = $p_url;
    }

    public function getRedirect(){

        return $this->redirect;
    }

    public function hasRedirect(){

        return ($this->redirect === false) ? false : true;
    }

    public function setCookie($p_key, $p_value){
        
        //TODO : Falta mas parametros para el cookie
        $this->cookies->set($p_key, $p_value);
    }

    public function getCookies(){

        return $this->cookies->all();
    }
}