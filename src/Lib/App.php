<?php

namespace Lib;
use Symfony\Component\EventDispatcher\EventDispatcher;



class App {
    
    protected static $routers;
    public static $request;
    
    public static $dispatcher ;
    
    public static $db;
    
    public static function getRouters(){
        
    return self::$routers;    
        
        
    }
    
    
    
    
    public static function run($uri){
        
        
      $dispatcher = new EventDispatcher(); 
      
      $dispatcher->addListener('data.view', array(new TestEvent(), 'dataView')); 
      
       self::$dispatcher=$dispatcher; 
        
  //============= router==============      
        
     self::$routers=new Router($uri);
     
  //------------connectionDB-----------------------   
     extract(Config::get('connectionDB'));
     
     self::$db=new DbPDO($host,$user,$password,$dbname);
     
  //---------------request--------------------------------------   
     
     self::$request=new Request;
   
   
   
  //==============create controller -> action================  
  
     $controller= 'Controllers\\'.self::$routers->getController();
     
        $action=self::$routers->getAction();
     
          $controller_object = new $controller();
  //==========================================================
  
      if(method_exists($controller_object,$action)) {
          
          
         $view_path=$controller_object->$action();
         
             $view_object=new View($controller_object->getData(),$view_path);
         
                   $content=$view_object->render();
         
                                                    } 
        
      else {
          
       throw new \Exception(" Нет метода $action в объекте $controller") ;  
          
           }  
      
      
      $layout=self::$routers->getRoute();
      
      
  //--------------проверка при входе в админ панель существует ли сессия админа    
      if($layout=='admin') {
          
          
        if(Session::get('role')!='admin') { App::redirect("/security/login") ;   };
          
          
      }
      
     
      
      $layout_path=VIEW_DIR.DS."$layout.html";  
        
      $layout_object=new View(compact('content'),$layout_path);
      
     echo $layout_object->render();
        
        
        
    }
    
    
   public static function redirect($to) {
       
      header('Location:'.$to);
      
      die();
       
   }
    
    
};