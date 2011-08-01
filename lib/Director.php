<?php

/**
* Creates singleton instances of required classes
*/

class Director{

  static private $director_instance = NULL;
  static private $manager_instance = NULL;
  static private $storage_instance = NULL;
      
  private $storage_engine;
  
  static function getInstance($config)
  {  
    if(self::$director_instance == NULL){
      self::$director_instance = new Director($config);
    }
    return self::$director_instance;
  }
  
  private function __construct($config=null)
  {  
    if(file_exists($config)){
      require $config;
    }
    switch($storage){
      case "pdo": $this->storage_engine = "PDOStorage"; break;
      case "file": $this->storage_engine = "FileStorage"; break;
      default : $this->storage_engine = "PDOStorage";
    }
  }
  
  //Factory-like method for creating instances  
  public static function build($type)
  {
    $class = $type;
    if (!class_exists($class)) {
        throw new Exception('!Warning: Missing class.');
    }
    return new $class;
  }
  
  function get_storage()
  {
    //get Storage instance depending on app configuration
    if(self::$storage_instance == NULL){
      self::$storage_instance = new $this->storage_engine();
    }    
    return self::$storage_instance;    
  }

  function get_manager()
  {
    //get AsyncManager instance
    $store = $this->get_storage();
    if(self::$manager_instance == NULL){
      self::$manager_instance = new AsyncManager($store);
    }    
    return self::$manager_instance;
  }
  
}
