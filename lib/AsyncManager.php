<?php
//Facade
class AsyncManager {

  private $storage = null;

  function __construct($storage) {
    $this->storage = $storage;
  }

  public function add_job($job) {
    return $this->storage->add($job);
  }

  public function execute_first_job() {
    $job = $this->storage->first();
    return $this->execute($job);
  }
  
  public function execute($job) {
  
    /**
    * Takes care of not executing 2 jobs simultaneously
    */  
  
    
    return $job->execute($job);    
  }
  
}
