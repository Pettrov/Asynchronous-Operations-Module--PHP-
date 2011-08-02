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
  
  public function add(Job $job)
  {
  
    // adds a job to the storage and returns the id
  
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
      $stringData[2] = 'N';      //N stands for 'Not executed'
      
      fputcsv($fp, $stringData, "#");

      fclose($fp);
      
      return $unique_id;  
  }
  
  public function get($id)
  {
    // retrieves a job by it's id  
    // retrieves the first job in the queue
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job
    $modified = array(); // array to keep all jobs

// Find the first Not started job ('N') and change its flag to 'S' (Started job)
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[2]=='N' and !$done and $line[0]==$id) {
          $my_array = $line;
          $line[2] = 'S'; //S stands for 'Started job'
          $done = 1;
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

// Unserialize the actual job and return it
    $job = unserialize($my_array[1]);
    
    if(!done) return false; //if no 'N' job was found
      
    return $job;     
        
  }
  
  public function all()
  {
    // retrieves all jobs
  }
  
  public function first()
  {
    // retrieves the first job in the queue
    $source = $this->storage_file;

    $done = 0;
    $sh = fopen($source, 'r+');

    $line = array(); // a row containing one job
    $modified = array(); // array to keep all jobs

// Find the first Not started job ('N') and change its flag to 'S' (Started job)
    while (!feof($sh)) {
      $line = fgetcsv($sh, 0, "#");
      if ($line[2]=='N' and !$done) {
          $my_array = $line;
          $line[2] = 'S'; //S stands for 'Started job'
          $done = 1;
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

// Unserialize the actual job and return it
    $job = unserialize($my_array[1]);
    
    if(!done) return false; //if no 'N' job was found
      
    return $job;     
    
  }
  
  public function status($id)
  {
    // retrieves the status of a job      
  }
  
  public function result($id)
  {
    // retrieves the result of a job
  }
}
