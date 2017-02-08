<?php

namespace Lib;


class Route {
    
    public $pattern;
    public $controller;
    public $action;
    public $params;
    
    
    public function __construct($pattern,$controller,$action,$params=array()){
        
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        
    }
    
    
    
    
    
}