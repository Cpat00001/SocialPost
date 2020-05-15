<?php
/*
    * App Core
    * this file Creates URL && Loads core controllers
    * URL FORMAT for access - /controller/method/params
*/
//error_reporting allows to decide what type of errors is shown/displayed, line belows hides Warning/Notice 
error_reporting(E_ALL & ~E_NOTICE);

class Core{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        //print_r($this->getUrl());
        $url = $this->getUrl();
        // Look in controllers for first value
      if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
        // If exists, set as controller
        $this->currentController = ucwords($url[0]);
        // Unset 0 Index
        unset($url[0]);
      }
        //Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        //Instantiate controller class
        $this->currentController = new $this->currentController;

        //check for second part of URL
        if(isset($url[1])){
            //check if method exists in a controller - use method_exists()
            if(method_exists($this->currentController,$url[1])){
                $this->currentMethod = $url[1];
                //unset index
                unset($url[1]);
            }
        }
        //Get params
        $this->params = $url ? array_values($url) : [];
        //call a callback with array of params
        call_user_func_array([$this->currentController,$this->currentMethod], $this->params);
    }

    public function getURL(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'],'/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode('/',$url);
            return $url;
        }
    }
}