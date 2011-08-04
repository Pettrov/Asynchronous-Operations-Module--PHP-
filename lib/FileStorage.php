<?php

// File Storage implementation

class FileStorage implements Storage {
  private $storage_file;
  public $get, $post, $files;
  public $file, $function, $arguments;
  
  function __construct()
  {
      include 'config/storage.php';
      $this->storage_file = $default_storage_file;
  }
  
  
  
/**
  ****************************************************
  *  Adds a job to the storage and returns the id
  ****************************************************
  */  
  public function add(Job $job)
  {  
      //Serialize the data here
      $job = serialize($job);
      
      if(file_exists($this->storage_file)){
        $unique_id = count(file($this->storage_file, FILE_SKIP_EMPTY_LINES))+1;          
      }
      else{
        $unique_id = 1;
      }
        
      $fp = fopen($this->storage_file, 'a+');

      $stringData[0] = $unique_id;      
      $stringData[1] = $job;
      $stringData[2] = '0';      //0 stands for 'Not executed yet'
      $stringData[3] = '0';      //0 by default = no result presented yet 
            
      fputcsv($fp, $stringData, "#");

      fclose($fp);
      
      return $unique_id;  
  }



/**
  ****************************************************
  *  Retrieves a job by it's id  
  ****************************************************
  */    
  public function get($id)
  {
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job

    // Find the first Not started job '0'
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[2]=='0' and !$done and $line[0]==$id) {
          $my_array = $line;
          $done = 1;
      }
    }
    fclose($sh);
    

    // Unserialize the actual job and return it
    $job = unserialize($my_array[1]);
    
    if(!$done) return false; //if no '0' job was found
  
    return $job;     
  }
  
  
  
/**
  ****************************************************
  *  Retrieves all jobs
  ****************************************************
  */      
  public function all($exec_only=false)
  {
    $source = $this->storage_file;
    $sh = fopen($source, 'r+');
    $modified = array(); // array to keep all jobs

    while (!feof($sh)) {

      $line = fgetcsv($sh, 0, "#");
      
      if($exec_only){      
        if ($line[2]=='0' or $line[2]=='2') {
              $line[1] = unserialize($line[1]);
              $modified[] = $line;
        }      
      }
      else{
        $modified[] = $line;
      }    
        
    }

    fclose($sh);

    if(!$modified) return false; //if no jobs were found
    
    return $modified;   
  }



/**
  ****************************************************
  *  Retrieves the first job in the queue
  ****************************************************
  */  
  public function first(&$id)
  {
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job

    // Find the first Not started job '0'
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[2]=='0' and !$done) {
          $my_array = $line;
          $done = 1;
      }
    }
    fclose($sh);

    // Unserialize the actual job and return it
    $job = unserialize($my_array[1]);
    
    $id = $my_array[0]; //passing the $id by reference

    if(!$done) return false; //if no '0' job was found
      
    return $job;     
  }
  
  
  
/**
  ****************************************************
  *  Retrieves the status of a job      
  ****************************************************
  */   
  public function status($id)
  {
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job

    // Find the Job ID we are looking for
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if (!$done and $line[0]==$id) {
          $my_status = $line[2];
          $done = 1;
      }
    }
    fclose($sh);

    if(!$done) $my_status = 9; //if no job was found
    return $my_status;     
  }
  
  
  
/**
  ****************************************************
  *  Retrieves the result of a job
  ****************************************************
  */  
  public function result($id)
  {
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job

    // Find the Job ID we are looking for
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if (!$done and $line[0]==$id) {
          $my_result = $line[3];
          $done = 1;
      }
    }
    fclose($sh);

    if(!$done) $my_result = false; //if no job was found
    return $my_result; 
  }



/**
  ****************************************************
  *  Sets the status of a job
  ****************************************************
  */  
  public function set_status($id, $status)
  {
    $source = $this->storage_file;

    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job
    $modified = array(); // array to keep all jobs

    // Find the first Not started job '0' and change its flag to '1' (Started job)
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[0]==$id) {
          $line[2] = $status;
      }
      $modified[] = $line;
    }
    fclose($sh);

    // Write the new data to the file    
    $fp = fopen($this->storage_file, 'w');
    foreach($modified as $row){
      @fputcsv($fp, $row, "#");
    }  
    fclose($fp);    

    return true; 
  }



/**
  ****************************************************
  *  Sets the result of a job
  ****************************************************
  */  
  public function set_result($id, $result)
  {
    $source = $this->storage_file;

    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job
    $modified = array(); // array to keep all jobs

    // Find the first Not started job '0' and change its flag to '1' (Started job)
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[0]==$id) {
          $line[3] = $result;
      }
      $modified[] = $line;
    }
    fclose($sh);

    // Write the new data to the file    
    $fp = fopen($this->storage_file, 'w');
    foreach($modified as $row){
      @fputcsv($fp, $row, "#");
    }  
    fclose($fp);    

    return true; 
  }
  
}
