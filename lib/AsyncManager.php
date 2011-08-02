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
    if($job){
      return $this->execute($job);
    }
    else
      return false;  
  }
  
  public function execute($job) {
  
    /**
    * Takes care of not executing 2 jobs simultaneously
    */  
    
    $fp = fopen("file_storage/tmp/lock.txt", "r+");
          
    if (flock($fp, LOCK_EX)) { // do an exclusive lock
        fwrite($fp, "Write some locking info\n");
        
        $execute = $job->execute($job);    

        flock($fp, LOCK_UN); // release the lock
    }
    
    fclose($fp);
    
    return $execute;    
  }
  
}
