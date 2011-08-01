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
    
      $fp = fopen($this->storage_file, 'a');
      
      $stringData = $job."\n";
      
      fwrite($fp, $stringData);
      
      //id of the inserted job
      $exit = 5;
      
      fclose($fp);
      
      return $exit;  
  }
  
  public function get($id)
  {
    // retrieves a job by it's id  
  }
  
  public function all()
  {
    // retrieves all jobs
  }
  
  public function first()
  {
    // retrieves the next job from the queue
    
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
